<?php

namespace App\Http\Controllers;

use http\Exception\RuntimeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\CarbonInterval;
use Temporal\Client\WorkflowOptions;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Api\Workflowservice\V1\DescribeWorkflowExecutionRequest;
use Temporal\Api\Workflowservice\V1\DescribeWorkflowExecutionResponse;
use Temporal\Api\Workflow\V1\WorkflowExecutionInfo;
use Temporal\Api\Common\V1\WorkflowExecution;
use Boomtown\Contracts\GreetingWorkflowInterface;
use Boomtown\Contracts\HelloWorkflowInterface;
use Boomtown\Implementations\FileProcessingWorkflow;
use App\Models\WorkflowRuns;
use App\Models\Workflows;
use Temporal\Common\Uuid;
use Illuminate\Support\Facades\DB;
use DateTime;
use Temporal\Workflow\WorkflowStub as WorkflowStubConverter;

class WorkflowPocController extends Controller
{
    private const RUN_VALIDATOR = [];

    protected WorkflowClientInterface $workflowClient;

    private const DEFAULT_TEMPORAL_HOST = 'temporal:7233';
    private const DEFAULT_TEMPORAL_ADMIN_HOST = 'http://host.docker.internal:8088';
    private const DEFAULT_TEMPORAL_ADMIN_WORKFLOW_URI = '/api/namespaces/default/workflows';

    protected $workflows = [
        'hello' => HelloWorkflowInterface::class,
        'file_processing' => FileProcessingWorkflow::class,
        'greeting' => GreetingWorkflowInterface::class
    ];

    protected function log($level, $message, $data = [])
    {
        if (env('RUN_ENVIRONMENT', 'local') == 'gcp') {
            Log::channel('stdout')->{$level}($message, $data);
        } else {
            Log::{$level}($message, $data);
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->workflowClient = WorkflowClient::create(
            ServiceClient::create(env('TEMPORAL_HOST', self::DEFAULT_TEMPORAL_HOST))
        );
    }

    protected function getTemporalWorkflowUrl($workflowInstanceId, $workflowRunId)
    {
        return env('TEMPORAL_ADMIN_HOST', self::DEFAULT_TEMPORAL_ADMIN_HOST) .
            env('TEMPORAL_ADMIN_WORKFLOW_URL', self::DEFAULT_TEMPORAL_ADMIN_WORKFLOW_URI) .
            '/' . $workflowInstanceId . '/' . $workflowRunId;
    }

    protected function getTemporalWorkflowHistoryUrl($workflowInstanceId, $workflowRunId)
    {
        return env('TEMPORAL_ADMIN_HOST', self::DEFAULT_TEMPORAL_ADMIN_HOST) .
            env('TEMPORAL_ADMIN_WORKFLOW_URL', self::DEFAULT_TEMPORAL_ADMIN_WORKFLOW_URI) .
            '/' . $workflowInstanceId . '/' . $workflowRunId . '/history?waitForNewEvent=true';
    }

    /**
     * @throws ValidationException
     */
    public function run(Request $request) : JsonResponse {
        $this->validate($request, self::RUN_VALIDATOR);
        $data = $request->all();

        $this->log('debug', 'Running POC Workflow', ['data' => $data]);

        $helloWorkflow = DB::table('workflows')
            ->select('uuid')
            ->where(['name' => 'HelloWorkflow'])
            ->get();

        $workflowInstanceUuid = Uuid::v4();
        $workflowName = $data['workflow_name'] ?? 'hello';
        if (isset($this->workflows[$workflowName])) {
            $workflowClass = $this->workflows[$workflowName];
        } else {
            $workflowClass = HelloWorkflowInterface::class;
        }
        $workflow = $this->workflowClient->newWorkflowStub(
            $workflowClass,
            WorkflowOptions::new()
                ->withWorkflowExecutionTimeout(CarbonInterval::minute(10))
                ->withWorkflowId($workflowInstanceUuid)
        );

        $this->log('debug', 'Starting POC Workflow');

        //$result = $workflow->greet('Yicheng');
        //$result = $workflow->processFile("https://file-examples-com.github.io/uploads/2017/10/file-sample_150kB.pdf", 'targetURL');
        $run = $this->workflowClient->start($workflow);
        $runUuid = $run->getExecution()->getRunID();
        $workflowUuid = $helloWorkflow[0]->uuid;
        if ($helloWorkflow && count($helloWorkflow) > 0) {
            $workflowRun = new WorkflowRuns();
            $workflowRun->uuid = $runUuid;
            $workflowRun->workflow_instance_uuid= $workflowInstanceUuid;
            $workflowRun->workflow_uuid = $workflowUuid;
            $workflowRun->input = json_encode([]);
            $workflowRun->metadata = json_encode([]);
            $workflowRun->start_at = new DateTime();
            $workflowRun->save();
        }
        $result = $run->getResult();
        $this->log('debug', 'Started POC Workflow', [ 'id' => $runUuid ]);

        $this->log('debug', 'Done Running POC Workflow', [ 'result' => $result ]);

        return response()->json([
            'result' => $result,
            'workflow_uuid' => $workflowRun->workflow_uuid,
            'workflow_run_uuid' => $workflowRun->uuid,
            'time' => date(DATE_ATOM)
        ]);
    }

    public function getStatus($uuid)
    {
        $this->log('debug', 'Query Workflow Status');

        $workflowRun = WorkflowRuns::findOrFail($uuid);
        $this->log('debug', 'Workflow Run found for '.$uuid);


        try {
            $workflowData = $this->getTemporalWorkflowData($workflowRun->workflow_instance_uuid, $workflowRun->uuid);
            $workflowHistoryData = $this->getTemporalWorkflowHistoryData($workflowRun->workflow_instance_uuid, $workflowRun->uuid);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json([
            'workflow' => json_decode($workflowData, true),
            'workflow_history' => json_decode($workflowHistoryData, true)
        ]);
    }

    protected function getTemporalWorkflowData($workflowInstanceUuid, $workflowRunUuid)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->getTemporalWorkflowUrl($workflowInstanceUuid, $workflowRunUuid),
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException('Curl Error: '.$err);
        }

        return $response;
    }

    protected function getTemporalWorkflowHistoryData($workflowInstanceUuid, $workflowRunUuid)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->getTemporalWorkflowHistoryUrl($workflowInstanceUuid, $workflowRunUuid),
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new RuntimeException('Curl Error: '.$err);
        }

        return $response;
    }
}
