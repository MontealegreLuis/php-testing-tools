<?php
/**
 * PHP version 7.0
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

    /**
     * @param EventSubscriber $subscriber
     */
    public function subscribe(EventSubscriber $subscriber)
    {
        $this->subscribers()->attach($subscriber);
    }

    /**
     * @param SplObjectStorage $events
     */
    public function publish(Traversable $events)
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @param Event $event
     */
    protected function dispatch(Event $event)
    {
        /** @var EventSubscriber $subscriber */
        foreach ($this->subscribers() as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }

    /**
     * @return SplObjectStorage
     */
    protected function subscribers()
    {
        if (!$this->subscribers) {
            $this->subscribers = new SplObjectStorage();
        }

        return $this->subscribers;
    }
}
