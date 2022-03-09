<?php

declare(strict_types=1);

namespace App\Activities;

// @@@SNIPSTART php-hello-activity-interface
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

interface HelloActivityInterface
{
    public function composeGreeting(): string;
}
// @@@SNIPEND
