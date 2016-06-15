<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\DomainEvents;

use Hexagonal\ContractTests\DomainEvents\EventStoreTest;
use Hexagonal\DomainEvents\{EventStore, StoredEvent};
use Ewallet\TestHelpers\ProvidesDoctrineSetup;

class EventStoreRepositoryTest extends EventStoreTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
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
    function storeInstance(): EventStore
    {
        return $this->entityManager->getRepository(StoredEvent::class);
    }
}
