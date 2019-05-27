<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\ContractTests\DomainEvents;

use Application\Clock;
use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEventFactory;
use Fakes\DomainEvents\InstantaneousEvent;
use PHPUnit\Framework\TestCase;
use Ports\JmsSerializer\Application\DomainEvents\JsonSerializer;

abstract class EventStoreTest extends TestCase
{
    /** @test */
    function it_retrieves_all_stored_events()
    {
        $events = $this->store->allEvents();

        $this->assertCount(4, $events);
    }

    /** @test */
    function it_retrieves_all_events_stored_after_a_given_event()
    {
        $events = $this->store->eventsStoredAfter($this->event2->id());

        $this->assertCount(2, $events);
    }

    /** @test */
    function it_retrieves_0_events_if_last_event_id_is_provided()
    {
        $events = $this->store->eventsStoredAfter($this->event4->id());

        $this->assertCount(0, $events);
    }

    /** @before */
    function generateFixtures(): void
    {
        $this->store = $this->storeInstance();
        $factory = new StoredEventFactory(new JsonSerializer());

        $instant = Clock::now();
        $event1 = $factory->from(new InstantaneousEvent($instant));
        $this->event2 = $factory->from(new InstantaneousEvent($instant));
        $event3 = $factory->from(new InstantaneousEvent($instant));
        $this->event4 = $factory->from(new InstantaneousEvent($instant));

        $this->store->append($event1);
        $this->store->append($this->event2);
        $this->store->append($event3);
        $this->store->append($this->event4);
    }

    abstract function storeInstance(): EventStore;

    /** @var EventStore */
    private $store;

    /** @var \Application\DomainEvents\StoredEvent */
    private $event2;

    /** @var \Application\DomainEvents\StoredEvent */
    private $event4;
}
