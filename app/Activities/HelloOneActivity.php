<?php

declare(strict_types=1);

namespace App\Activities;

use Temporal\Activity;

// @@@SNIPSTART php-hello-one-activity
#[Activity\ActivityInterface(prefix: "HelloOne.")]
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
