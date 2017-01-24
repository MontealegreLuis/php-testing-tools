<?php
/**
 * PHP version 7.1
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

    public function __construct(
        EventStore $eventStore,
        StoredEventFactory $eventFactory
    ) {
        $this->eventStore = $eventStore;
        $this->eventFactory = $eventFactory;
    }

    public function isSubscribedTo(Event $event): bool
    {
        return true;
    }

    /**
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $this->eventStore->append($this->eventFactory->from($event));
    }
}
