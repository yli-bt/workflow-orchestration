<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Temporal\Activity;
use Boomtown\Contracts\HelloActivityInterface;

// @@@SNIPSTART php-hello-two-activity
#[ActivityInterface(prefix: "HelloTwo.")]
class HelloTwoActivity implements HelloActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Moe';

    public function composeGreeting(): string
    {
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
