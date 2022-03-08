<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Temporal\Activity;
use Boomtown\Contracts\HelloActivityInterface;

// @@@SNIPSTART php-hello-two-activity
#[ActivityInterface(prefix: "HelloThree.")]
class HelloThreeActivity implements HelloActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Curly';

    public function composeGreeting(): string
    {
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
