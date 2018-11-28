<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\DomainEvents;

use Hexagonal\ContractTests\DomainEvents\EventStoreTest;
use Hexagonal\DomainEvents\{EventStore, StoredEvent};
use Ewallet\Doctrine\ProvidesDoctrineSetup;
use Ports\Doctrine\DomainEvents\EventStoreRepository;

class EventStoreRepositoryTest extends EventStoreTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
        self::$entityManager
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    function storeInstance(): EventStore
    {
        return new EventStoreRepository(self::$entityManager);
    }
}
