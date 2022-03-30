<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Boomtown\Implementations;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Workflow;
use Boomtown\Contracts\GreetingWorkflowInterface;

// @@@SNIPSTART php-hello-workflow
class GreetingWorkflow implements GreetingWorkflowInterface
{
    private $greetingActivities = [];

    public function __construct()
    {
        /**
         * Activity stub implements activity interface and proxies calls to it to Temporal activity
         * invocations. Because activities are reentrant, only a single stub can be used for multiple
         * activity invocations.
         */
        $this->greetingActivities = [
            Workflow::newActivityStub(
                HelloActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
            Workflow::newActivityStub(
                GreetingActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
            Workflow::newActivityStub(
                ByeActivity::class,
                ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
            ),
        ];
    }

    public function greet(): \Generator
    {
        $results = [];
        foreach ($this->greetingActivities as $activity) {
            $results[] = yield $activity->composeGreeting();
        }

        return $results;
    }
}
// @@@SNIPEND
