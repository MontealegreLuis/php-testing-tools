<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Application\DomainEvents;

use Application\ContractTests\DomainEvents\EventStoreTest;
use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEvent;
use Doctrine\DataStorageSetup;

class EventStoreRepositoryTest extends EventStoreTest
{
    /** @before */
    function generateFixtures(): void
    {
        $this->setup = new DataStorageSetup(require __DIR__ . '/../../../../../../config.php');
        $this->setup->updateSchema();
        $this->setup->entityManager()->createQuery('DELETE FROM ' . StoredEvent::class)->execute();
        parent::generateFixtures();
    }

    public function storeInstance(): EventStore
    {
        return new EventStoreRepository($this->setup->entityManager());
    }

    /** @var DataStorageSetup */
    private $setup;
}
