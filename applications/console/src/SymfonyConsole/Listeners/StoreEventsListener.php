<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Listeners;

use Hexagonal\DomainEvents\{EventPublisher, PersistEventsSubscriber};
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class StoreEventsListener
{
    /** @var PersistEventsSubscriber */
    private $subscriber;

    /** @var EventPublisher */
    private $publisher;

    public function __construct(
        PersistEventsSubscriber $subscriber,
        EventPublisher $publisher
    ) {
        $this->subscriber = $subscriber;
        $this->publisher = $publisher;
    }

    /**
     * Run from the `ewallet:transfer` command only
     */
    public function storeEvents(ConsoleCommandEvent $event): void
    {
        if ('ewallet:transfer' === $event->getCommand()->getName()) {
            $this->publisher->subscribe($this->subscriber);
        }
    }

    public function __invoke(ConsoleCommandEvent $event)
    {
        $this->storeEvents($event);
    }
}
