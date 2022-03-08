<?php

declare(strict_types=1);

namespace Boomtown\Contracts;

// @@@SNIPSTART php-hello-activity-interface
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

interface HelloActivityInterface
{
    public function composeGreeting(): string;
}
// @@@SNIPEND
