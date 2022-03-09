<?php

declare(strict_types=1);

namespace App\Activities;

use Temporal\Activity;

// @@@SNIPSTART php-hello-two-activity
#[Activity\ActivityInterface(prefix: "HelloThree.")]
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
