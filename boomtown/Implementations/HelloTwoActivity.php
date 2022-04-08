<?php

declare(strict_types=1);

namespace Boomtown\Implementations;

use Boomtown\Contracts\HelloTwoActivityInterface;

// @@@SNIPSTART php-hello-two-activity
class HelloTwoActivity implements HelloTwoActivityInterface
{
    private $greeting = 'Hello';
    private $name = 'Moe';

    #[ActivityMethod("composeGreeting")]
    public function composeGreeting(): string
    {
        sleep(20);
        return $this->greeting . ' ' . $this->name;
    }
}
// @@@SNIPEND
