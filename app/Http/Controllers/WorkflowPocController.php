<?php

namespace App\Http\Controllers;

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

        $runUuid = Uuid::v4();
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
                ->withWorkflowId($runUuid)
        );

        $this->log('debug', 'Starting POC Workflow');

        if ($helloWorkflow && count($helloWorkflow) > 0) {
            $workflowRun = new WorkflowRuns();
            $workflowRun->uuid = $runUuid;
            $workflowRun->workflow_uuid = $helloWorkflow[0]->uuid;
            $workflowRun->input = json_encode([]);
            $workflowRun->metadata = json_encode([]);
            $workflowRun->start_at = new DateTime();
            $workflowRun->save();
        }

        //$result = $workflow->greet('Yicheng');
        //$result = $workflow->processFile("https://file-examples-com.github.io/uploads/2017/10/file-sample_150kB.pdf", 'targetURL');
        $run = $this->workflowClient->start($workflow);
        $result = $run->getResult();
        $this->log('debug', 'Started POC Workflow', [ 'id' => $run->getExecution()->getID() ]);

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
        $workflow = Workflows::findOrFail($workflowRun->workflow_uuid);
        $this->log('debug', 'Workflow Run found for '.$uuid);

        $workflowExecution = new WorkflowExecution();
        $workflowExecution->setRunId($workflowRun->uuid);
        $workflowExecution->setWorkflowId($workflowRun->workflow_uuid);

        $options = WorkflowOptions::new();
        $describeWorkflowExecutionRequest = new DescribeWorkflowExecutionRequest();
        $describeWorkflowExecutionRequest->setNamespace('default');
        $describeWorkflowExecutionRequest->setExecution($workflowExecution);

        $this->log('debug', 'Describe Workflow Execution');
        $describeWorkflowExecutionResponse = $this->workflowClient->getServiceClient()->DescribeWorkflowExecution(
            $describeWorkflowExecutionRequest
        );

        $this->log('debug', 'Get Workflow Execution Info');
        $workflowExecutionInfo = $describeWorkflowExecutionResponse->getWorkflowExecutionInfo();

        $this->log('debug', 'Return Response');
        return response()->json($workflowExecutionInfo);
    }
}
