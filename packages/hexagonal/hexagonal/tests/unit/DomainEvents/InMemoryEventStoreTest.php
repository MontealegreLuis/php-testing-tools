<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use Hexagonal\ContractTests\DomainEvents\EventStoreTest;

class InMemoryEventStoreTest extends EventStoreTest
{
    function storeInstance(): EventStore
    {
        return new InMemoryEventStore();
    }
}
