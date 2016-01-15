<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

class StoredEventFactory
{
    /** @var EventSerializer */
    private $serializer;

    /**
     * @param EventSerializer $serializer
     */
    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Event $event
     * @param EventSerializer $serializer
     * @return StoredEvent
     */
    public function from(Event $event)
    {
        return new StoredEvent(
            $this->serializer->serialize($event),
            get_class($event),
            $event->occurredOn()
        );
    }
}
