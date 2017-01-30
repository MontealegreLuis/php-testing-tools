<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Hexagonal\ContractTests\Messaging\MessageTrackerTest;

class InMemoryMessageTrackerTest extends MessageTrackerTest
{
    function messageTracker(): MessageTracker
    {
        return new InMemoryMessageTracker();
    }
}
