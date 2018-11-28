<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use SplObjectStorage;
use Traversable;

class EventPublisher
{
    /** @var SplObjectStorage */
    private $subscribers;

    public function subscribe(EventSubscriber $subscriber): void
    {
        $this->subscribers()->attach($subscriber);
    }

    public function publish(Traversable $events): void
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    protected function dispatch(Event $event): void
    {
        /** @var EventSubscriber $subscriber */
        foreach ($this->subscribers() as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }

    protected function subscribers(): SplObjectStorage
    {
        if (!$this->subscribers) {
            $this->subscribers = new SplObjectStorage();
        }

        return $this->subscribers;
    }
}
