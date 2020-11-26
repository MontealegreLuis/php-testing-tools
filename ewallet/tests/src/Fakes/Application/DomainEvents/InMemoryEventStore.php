<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application\DomainEvents;

use ReflectionException;
use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEvent;
use ReflectionObject;

class InMemoryEventStore implements EventStore
{
    private static int $nextId = 1;

    /** @var StoredEvent[] */
    private array $events;

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

    /** @throws ReflectionException */
    public function append(StoredEvent $anEvent): void
    {
        $this->assignIdTo($anEvent);

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
            if ($addEvents) {
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

    /** @throws ReflectionException */
    private function assignIdTo(StoredEvent $anEvent): void
    {
        $event = new ReflectionObject($anEvent);
        $identifier = $event->getProperty('id');
        $identifier->setAccessible(true);
        $value = $identifier->getValue($anEvent);
        if ($value === null) {
            $identifier->setValue($anEvent, self::$nextId);
            self::$nextId++;
        }
    }
}
