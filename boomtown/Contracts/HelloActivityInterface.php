<?php

declare(strict_types=1);

namespace Boomtown\Contracts;

use Temporal\Activity\ActivityMethod;

interface HelloActivityInterface
{
    #[ActivityMethod("composeGreeting")]
    public function composeGreeting(): string;
}
// @@@SNIPEND
