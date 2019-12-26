<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\DomainEvents;

use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEvent;
use ContractTests\Application\DomainEvents\EventStoreTest;
use Doctrine\DataStorageSetup;

class EventStoreRepositoryTest extends EventStoreTest
{
    /** @before */
    function let()
    {
        $this->setup = new DataStorageSetup(require __DIR__ . '/../../../../../../config/config.php');
        $this->setup->updateSchema();
        $this->setup->entityManager()->createQuery('DELETE FROM ' . StoredEvent::class)->execute();
        parent::let();
    }

    public function storeInstance(): EventStore
    {
        return new EventStoreRepository($this->setup->entityManager());
    }

    /** @var DataStorageSetup */
    private $setup;
}
