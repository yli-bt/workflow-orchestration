<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Temporal\Activity;
use Boomtown\Contracts\HelloActivityInterface;

// @@@SNIPSTART php-hello-one-activity
#[ActivityInterface(prefix: "HelloOne.")]
class HelloOneActivity implements HelloActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Larry';

    public function composeGreeting(): string
    {
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
