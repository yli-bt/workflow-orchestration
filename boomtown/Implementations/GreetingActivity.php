<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Boomtown\Implementations;

use Boomtown\Contracts\GreetingActivityInterface;

/**
 * Activity definition interface. Must redefine the name of the composeGreeting activity to avoid
 * collision.
 */
#[ActivityInterface(prefix: "Greeting.")]
class GreetingActivity implements GreetingActivityInterface
{
    #[ActivityMethod("composeGreeting")]
    public function composeGreeting(): string
    {
        $name = 'Moe';
        return 'Greetings, ' . $name . '!';
    }
}
