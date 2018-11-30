<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Application\DomainEvents;

use Application\ContractTests\DomainEvents\EventStoreTest;
use Doctrine\ProvidesDoctrineSetup;

class EventStoreRepositoryTest extends EventStoreTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../../../config.php');
        self::$entityManager
            ->createQuery('DELETE FROM ' . \Application\DomainEvents\StoredEvent::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    function storeInstance(): \Application\DomainEvents\EventStore
    {
        return new EventStoreRepository(self::$entityManager);
    }
}
