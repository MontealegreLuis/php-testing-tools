<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\DomainEvents;

use Doctrine\ORM\EntityRepository;
use Hexagonal\DomainEvents\{EventStore, StoredEvent};

class EventStoreRepository extends EntityRepository implements EventStore
{
    public function append(StoredEvent $anEvent)
    {
        $this->_em->persist($anEvent);
        $this->_em->flush($anEvent);
    }

    /**
     * @return StoredEvent[]
     */
    public function eventsStoredAfter(int $lastStoredEventId): array
    {
        $query = $this->createQueryBuilder('e');
        $query
            ->where('e.id > :eventId')
            ->setParameter('eventId', $lastStoredEventId)
            ->orderBy('e.id')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @return StoredEvent[]
     */
    public function allEvents(): array
    {
        $query = $this->createQueryBuilder('e')->orderBy('e.id');

        return $query->getQuery()->getResult();
    }
}
