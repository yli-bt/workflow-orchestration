<?php

declare(strict_types=1);

use Temporal\WorkerFactory;
use App\Activities\HelloOneActivity;

ini_set('display_errors', 'stderr');
include('vendor/autoload.php');

$workflowTypes = [
    'App\Workflows\HelloWorkflow'
];

$activityTypes = [
    'App\Activities\HelloOneActivity',
    'App\Activities\HelloTwoActivity',
    'App\Activities\HelloThreeActivity'
];

$factory = WorkerFactory::create();
$worker = $factory->newWorker();

foreach ($workflowTypes as $workflowType) {
    $worker->registerWorkflowTypes($workflowType);
}


foreach ($activityTypes as $activityType) {
    $worker->registerActivityImplementations(new $activityType());
}

$hostTaskQueue = gethostname();

$factory->newWorker($hostTaskQueue)
    ->registerActivityImplementations(new HelloOneActivity($hostTaskQueue));

$factory->run();

