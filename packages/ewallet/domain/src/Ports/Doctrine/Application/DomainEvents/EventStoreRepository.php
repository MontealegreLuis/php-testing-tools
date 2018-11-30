<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Application\DomainEvents;

use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEvent;
use Ports\Doctrine\Application\DataStorage\Repository;

class EventStoreRepository extends Repository implements EventStore
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function append(StoredEvent $anEvent): void
    {
        $this->manager->persist($anEvent);
        $this->manager->flush($anEvent);
    }

    /**
     * @return StoredEvent[]
     */
    public function eventsStoredAfter(int $lastStoredEventId): array
    {
        $query = $this->manager->createQueryBuilder();
        $query
            ->select('e')
            ->from(StoredEvent::class, 'e')
            ->where('e.id > :eventId')
            ->setParameter('eventId', $lastStoredEventId)
            ->orderBy('e.id');

        return $query->getQuery()->getResult();
    }

    /**
     * @return StoredEvent[]
     */
    public function allEvents(): array
    {
        $query = $this->manager->createQueryBuilder();
        $query
            ->select('e')
            ->from(StoredEvent::class, 'e')
            ->orderBy('e.id');

        return $query->getQuery()->getResult();
    }
}
