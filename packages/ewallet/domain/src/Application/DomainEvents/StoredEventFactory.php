<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

class StoredEventFactory
{
    /** @var EventSerializer */
    private $serializer;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function from(Event $event): StoredEvent
    {
        return new StoredEvent(
            $this->serializer->serialize($event),
            get_class($event),
            $event->occurredOn()
        );
    }
}
