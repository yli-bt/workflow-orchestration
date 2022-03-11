<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Temporal\WorkerFactory;
use Illuminate\Console\Command;
use Boomtown\Implementations\StoreActivity;
use Boomtown\Utils\DeclarationLocator;

class WorkflowWorkerCommand extends Command
{
    protected $signature = 'workflow:worker';

    protected $description = 'Run Workflow Worker';

    public function handle()
    {
        ini_set('display_errors', 'stderr');

        $declarations = DeclarationLocator::create(base_path() . '/boomtown');

        $factory = WorkerFactory::create();
        $worker = $factory->newWorker();

        foreach ($declarations->getWorkflowTypes() as $workflowType) {
            $worker->registerWorkflowTypes($workflowType);
        }

        foreach ($declarations->getActivityTypes() as $activityType) {
            $worker->registerActivityImplementations(new $activityType());
        }

        $hostTaskQueue = gethostname();

        $factory->newWorker($hostTaskQueue)
            ->registerActivityImplementations(new StoreActivity($hostTaskQueue));

        $factory->run();
    }

}
