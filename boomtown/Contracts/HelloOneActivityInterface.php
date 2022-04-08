<?php

declare(strict_types=1);

namespace Boomtown\Contracts;

// @@@SNIPSTART php-hello-one-activity-interface
use Temporal\Activity\ActivityInterface;

#[ActivityInterface(prefix:"HelloOne.")]
interface HelloOneActivityInterface
{
    public function composeGreeting(): string;
}
// @@@SNIPEND
