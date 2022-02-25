<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Activities;

use Temporal\Activity;

/**
 * Activity definition interface. Must redefine the name of the composeGreeting activity to avoid
 * collision.
 */
#[Activity\ActivityInterface(prefix: "Greeting.")]
class GreetingActivity implements GreetingActivityInterface
{
    public function composeGreeting(): string
    {
        $name = 'Moe';
        return 'Greetings, ' . $name . '!';
    }
}
