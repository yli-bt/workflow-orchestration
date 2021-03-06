<?php

declare(strict_types=1);

namespace Boomtown\Contracts;

// @@@SNIPSTART php-hello-activity-interface
use Temporal\Activity\ActivityInterface;

#[ActivityInterface(prefix:"HelloThree.")]
interface HelloThreeActivityInterface
{
    public function composeGreeting(): string;
}
// @@@SNIPEND
