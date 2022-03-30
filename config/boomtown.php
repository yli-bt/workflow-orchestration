<?php

use Illuminate\Support\Str;
use App\Http\Controllers\WorkflowPocController;

return [

    'temporal' => [

        'host' => env('TEMPORAL_HOST', WorkflowPocController::DEFAULT_TEMPORAL_HOST),

        'admin' => [
            'host' => env('TEMPORAL_ADMIN_HOST', WorkflowPocController::DEFAULT_TEMPORAL_ADMIN_HOST),
            'workflow_url' => env('TEMPORAL_ADMIN_WORKFLOW_URL', WorkflowPocController::DEFAULT_TEMPORAL_ADMIN_WORKFLOW_URI)
        ],

    ],

];
