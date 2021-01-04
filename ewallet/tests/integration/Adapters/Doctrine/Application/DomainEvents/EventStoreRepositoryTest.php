<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\DomainEvents;

use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEvent;
use ContractTests\Application\DomainEvents\EventStoreTest;
use Doctrine\WithDatabaseSetup;
use SplFileInfo;

final class EventStoreRepositoryTest extends EventStoreTest
{
    use WithDatabaseSetup;

    /** @before */
    function let()
    {
        $this->_setupDatabaseSchema(new SplFileInfo(__DIR__ . '/../../../../../../'));
        $this->_executeDqlQuery('DELETE FROM ' . StoredEvent::class);
        parent::let();
    }

    public function storeInstance(): EventStore
    {
        return new EventStoreRepository($this->setup->entityManager());
    }
}
