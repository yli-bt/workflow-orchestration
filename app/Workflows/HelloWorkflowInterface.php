<?php

declare(strict_types=1);

namespace App\Workflows;

// @@@SNIPSTART php-hello-workflow-interface
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface HelloWorkflowInterface
{
    /**
     * @param string $name
     * @return string
     */
    #[WorkflowMethod(name: "Hello.greet")]
    #[ReturnType("string")]
    public function greet();
}
// @@@SNIPEND
