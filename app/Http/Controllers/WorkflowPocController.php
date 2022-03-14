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
use Boomtown\Contracts\GreetingWorkflowInterface;
use Boomtown\Contracts\HelloWorkflowInterface;
use Boomtown\Implementations\FileProcessingWorkflow;

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

        $workflow = $this->workflowClient->newWorkflowStub(
            HelloWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute(10))
        );

        $this->log('debug', 'Starting POC Workflow');

        //$result = $workflow->greet('Yicheng');
        //$result = $workflow->processFile("https://file-examples-com.github.io/uploads/2017/10/file-sample_150kB.pdf", 'targetURL');
        $run = $this->workflowClient->start($workflow);
        $result = $run->getResult();
        $this->log('debug', 'Started POC Workflow', [ 'id' => $run->getExecution()->getID() ]);

        $this->log('debug', 'Done Running POC Workflow', [ 'result' => $result ]);

        return response()->json([
            'result' => $result,
            'time' => date(DATE_ATOM)
        ]);
    }
}
