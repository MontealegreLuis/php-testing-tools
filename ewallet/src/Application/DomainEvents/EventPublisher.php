<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * A publisher notifies a group of event subscribers when domain events have been published
 */
class EventPublisher
{
    /** @var EventSubscriber[] */
    private $subscribers;

    public function __construct()
    {
        $this->subscribers = [];
    }

    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    /** @param Event[] $events */
    public function publish(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    protected function dispatch(Event $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }
}
