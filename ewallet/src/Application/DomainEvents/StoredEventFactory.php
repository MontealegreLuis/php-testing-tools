<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Transforms domain events into stored events in order to persist them and publish them via messaging later
 */
final class StoredEventFactory
{
    private EventSerializer $serializer;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function from(DomainEvent $event): StoredEvent
    {
        return new StoredEvent(
            $this->serializer->serialize($event),
            get_class($event),
            $event->occurredOn()
        );
    }
}
