<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Generic event subscriber that saves all domain events to a data store via an event store
 */
final class PersistEventsSubscriber implements EventSubscriber
{
    private EventStore $eventStore;
    private StoredEventFactory $eventFactory;

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
