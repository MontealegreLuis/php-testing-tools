<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

/**
 * Provide a mechanism to persist domain events in order to spread them across
 * bounded contexts
 */
interface EventStore
{
    /**
     * @param StoredEvent $anEvent
     */
    public function append(StoredEvent $anEvent);

    /**
     * @param $lastStoredEventId
     * @return StoredEvent[]
     */
    public function eventsStoredAfter($lastStoredEventId = null);
}
