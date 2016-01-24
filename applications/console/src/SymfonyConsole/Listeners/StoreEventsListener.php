<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Listeners;

use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class StoreEventsListener
{
    /** @var PersistEventsSubscriber */
    private $subscriber;

    /** @var EventPublisher */
    private $publisher;

    /**
     * @param PersistEventsSubscriber $subscriber
     * @param EventPublisher $publisher
     */
    public function __construct(
        PersistEventsSubscriber $subscriber,
        EventPublisher $publisher
    ) {
        $this->subscriber = $subscriber;
        $this->publisher = $publisher;
    }

    /**
     * Register the subscriber that stores domain events only for the
     * `ewallet:transfer` command
     *
     * @param ConsoleCommandEvent $event
     */
    public function storeEvents(ConsoleCommandEvent $event)
    {
        if ('ewallet:transfer' === $event->getCommand()->getName()) {
            $this->publisher->subscribe($this->subscriber);
        }
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function __invoke(ConsoleCommandEvent $event)
    {
        $this->storeEvents($event);
    }
}
