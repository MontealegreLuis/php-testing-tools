<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use ReflectionObject;

class InMemoryEventStore implements EventStore
{
    /** @var int */
    private static $nextId = 1;

    /** @var StoredEvent[] */
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    /**
     * @param StoredEvent[] $events
     */
    public function appendAll(array $events): void
    {
        foreach ($events as $event) {
            $this->append($event);
        }
    }

    public function append(StoredEvent $anEvent): void
    {
        if ($anEvent->id() === 0) { // Doesn't have an ID
            $this->assignIdTo($anEvent);
        }

        $this->events[] = $anEvent;
    }

    /**
     * @return StoredEvent[]
     */
    public function eventsStoredAfter(int $lastStoredEventId): array
    {
        $addEvents = false;
        $eventsAfter = [];
        foreach ($this->events as $event) {
            if (true === $addEvents) {
                $eventsAfter[] = $event;
            }
            if ($event->id() === $lastStoredEventId) {
                $addEvents = true;
            }
        }
        return $eventsAfter;
    }

    /**
     * @return StoredEvent[]
     */
    public function allEvents(): array
    {
        return $this->events;
    }

    private function assignIdTo(StoredEvent $anEvent): void
    {
        $event = new ReflectionObject($anEvent);
        $identifier = $event->getProperty('id');
        $identifier->setAccessible(true);
        $identifier->setValue($anEvent, self::$nextId);
        self::$nextId++;
    }
}
