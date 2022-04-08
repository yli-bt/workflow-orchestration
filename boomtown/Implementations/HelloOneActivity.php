<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Boomtown\Contracts\HelloOneActivityInterface;

// @@@SNIPSTART php-hello-one-activity
class HelloOneActivity implements HelloOneActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Larry';

    #[ActivityMethod("composeGreeting")]
    public function composeGreeting(): string
    {
        sleep(20);
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
