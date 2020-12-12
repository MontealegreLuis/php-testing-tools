<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * A publisher notifies a group of event subscribers when domain events have been published
 *
 * @noRector Rector\SOLID\Rector\Class_\FinalizeClassesWithoutChildrenRector
 */
class EventPublisher
{
    /** @var EventSubscriber[] */
    private array $subscribers;

    public function __construct()
    {
        $this->subscribers = [];
    }

    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    /** @param DomainEvent[] $events */
    public function publish(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    protected function dispatch(DomainEvent $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }
}
