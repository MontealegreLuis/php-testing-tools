<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\DomainEvents;

use Doctrine\ORM\EntityRepository;
use Hexagonal\DomainEvents\EventStore;
use Hexagonal\DomainEvents\StoredEvent;

class EventStoreRepository extends EntityRepository implements EventStore
{
    /**
     * @param StoredEvent $anEvent
     */
    public function append(StoredEvent $anEvent)
    {
        $this->_em->persist($anEvent);
        $this->_em->flush($anEvent);
    }

    /**
     * @param integer $lastStoredEventId
     * @return StoredEvent[]
     */
    public function eventsStoredAfter($lastStoredEventId)
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
    public function allEvents()
    {
        $query = $this->createQueryBuilder('e')->orderBy('e.id');

        return $query->getQuery()->getResult();
    }
}
