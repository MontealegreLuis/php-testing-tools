<?php
/**
 * PHP version 7.0
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
     * @param int $lastStoredEventId
     * @return StoredEvent[]
     */
    public function eventsStoredAfter(int $lastStoredEventId): array;

    /**
     * @return StoredEvent[]
     */
    public function allEvents(): array;
}
