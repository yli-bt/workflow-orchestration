<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Temporal\Activity;
use Boomtown\Contracts\HelloThreeActivityInterface;

// @@@SNIPSTART php-hello-two-activity
class HelloThreeActivity implements HelloThreeActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Curly';

    #[ActivityMethod("composeGreeting")]
    public function composeGreeting(): string
    {
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
