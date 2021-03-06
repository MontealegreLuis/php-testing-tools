<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Messaging;

use ContractTests\Application\Messaging\MessageTrackerTest;
use Fakes\Application\Messaging\InMemoryMessageTracker;

class InMemoryMessageTrackerTest extends MessageTrackerTest
{
    function messageTracker(): MessageTracker
    {
        return new InMemoryMessageTracker();
    }
}
