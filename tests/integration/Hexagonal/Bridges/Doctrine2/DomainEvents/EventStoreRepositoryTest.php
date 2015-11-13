<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\Doctrine2\DomainEvents;

use ContractTests\Hexagonal\DomainEvents\EventStoreTest;
use Hexagonal\DomainEvents\EventStore;
use Hexagonal\DomainEvents\StoredEvent;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class EventStoreRepositoryTest extends EventStoreTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . StoredEvent::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    /**
     * @return EventStore
     */
    function storeInstance()
    {
        return $this->entityManager->getRepository(StoredEvent::class);
    }
}
