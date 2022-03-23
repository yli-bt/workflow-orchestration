<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Workflow;
use Boomtown\Contracts\HelloWorkflowInterface;
use Boomtown\Contracts\HelloOneActivityInterface;
use Boomtown\Contracts\HelloTwoActivityInterface;
use Boomtown\Contracts\HelloThreeActivityInterface;

// @@@SNIPSTART php-hello-workflow
class HelloWorkflow implements HelloWorkflowInterface
{
    private $activities = [];

    public function __construct()
    {
        /**
         * Activity stub implements activity interface and proxies calls to it to Temporal activity
         * invocations. Because activities are reentrant, only a single stub can be used for multiple
         * activity invocations.
         */
        $this->activities = [
            Workflow::newActivityStub(
                HelloOneActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
            Workflow::newActivityStub(
                HelloTwoActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
            Workflow::newActivityStub(
                HelloThreeActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
        ];
    }

    public function greet(): \Generator
    {
        $results = [];
        for($i = 0; $i < count($this->activities); $i++) {
            $results[] = yield $this->activities[$i]->composeGreeting();
        }
        return $results;
    }
}
// @@@SNIPEND
