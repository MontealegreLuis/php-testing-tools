<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use ContractTests\Application\DomainEvents\EventStoreTest;
use Fakes\Application\DomainEvents\InMemoryEventStore;

class InMemoryEventStoreTest extends EventStoreTest
{
    function storeInstance(): EventStore
    {
        return new InMemoryEventStore();
    }
}
