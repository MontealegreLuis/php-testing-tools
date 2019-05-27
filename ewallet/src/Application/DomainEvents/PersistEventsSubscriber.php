<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Generic event subscriber that saves all domain events to a data store via an event store
 */
class PersistEventsSubscriber implements EventSubscriber
{
    /** @var EventStore */
    private $eventStore;

    /** @var StoredEventFactory */
    private $eventFactory;

    public function __construct(EventStore $eventStore, StoredEventFactory $eventFactory)
    {
        $this->eventStore = $eventStore;
        $this->eventFactory = $eventFactory;
    }

    public function isSubscribedTo(DomainEvent $event): bool
    {
        return true;
    }

    public function handle(DomainEvent $event): void
    {
        $this->eventStore->append($this->eventFactory->from($event));
    }
}
