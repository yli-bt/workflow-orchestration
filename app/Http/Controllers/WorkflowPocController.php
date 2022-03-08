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
use Boomtown\Implemenations\FileProcessingWorkflow;

class WorkflowPocController extends Controller
{
    private const RUN_VALIDATOR = [];
    const DEFAULT_TEMPORAL_HOST = '127.0.0.1';
    const DEFAULT_TEMPORAL_PORT = '7233';
    const TEMPORAL_HOST_ENV = 'TEMPORAL_HOST';
    const TEMPORAL_PORT_ENV = 'TEMPORAL_PORT';

    protected WorkflowClientInterface $workflowClient;

    protected $workflows = [
        'hello' => HelloWorkflowInterface::class,
        'file_processing' => FileProcessingWorkflow::class,
        'greeting' => GreetingWorkflowInterface::class
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->workflowClient = WorkflowClient::create(ServiceClient::create($this->getTemporalHost()));
    }

    /**
     * Retrieves temporal host/port connection string
     *
     * @return string
     */
    protected function getTemporalHost(): string
    {
        if ($temporalHost = env(self::TEMPORAL_HOST_ENV) &&
            $temporalPort = env(self::TEMPORAL_PORT_ENV)
        ) {
            return $temporalHost . ':' . $temporalPort;
        } else {
            return self::DEFAULT_TEMPORAL_HOST . ':' . self::DEFAULT_TEMPORAL_PORT;
        }
    }

    /**
     * @throws ValidationException
     */
    public function run(Request $request) : JsonResponse {
        $this->validate($request, self::RUN_VALIDATOR);

        $data = $request->all();

        Log::debug('Running POC Workflow', ['data' => $data]);

        $workflow = $this->workflowClient->newWorkflowStub(
            HelloWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute(10))
        );

        Log::debug('Starting POC Workflow');

        //$result = $workflow->greet('Yicheng');
        //$result = $workflow->processFile("https://file-examples-com.github.io/uploads/2017/10/file-sample_150kB.pdf", 'targetURL');
        $run = $this->workflowClient->start($workflow);
        $result = $run->getResult();
        Log::debug('Started POC Workflow', [ 'id' => $run->getExecution()->getID() ]);

        Log::debug('Done Running POC Workflow', [ 'result' => $result ]);

        return response()->json([
            'result' => $result,
            'time' => date(DATE_ATOM)
        ]);
    }
}
