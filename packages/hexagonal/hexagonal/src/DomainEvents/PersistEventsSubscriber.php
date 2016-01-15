<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

class PersistEventsSubscriber implements EventSubscriber
{
    /** @var EventStore */
    private $eventStore;

    /** @var StoredEventFactory */
    private $eventFactory;

    /**
     * @param EventStore $eventStore
     * @param StoredEventFactory $eventFactory
     */
    public function __construct(
        EventStore $eventStore,
        StoredEventFactory $eventFactory
    ) {
        $this->eventStore = $eventStore;
        $this->eventFactory = $eventFactory;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function isSubscribedTo(Event $event)
    {
        return true;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function handle(Event $event)
    {
        $this->eventStore->append($this->eventFactory->from($event));
    }
}
