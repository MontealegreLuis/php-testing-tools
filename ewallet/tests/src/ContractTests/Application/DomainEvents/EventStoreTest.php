<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ContractTests\Application\DomainEvents;

use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Application\DomainEvents\EventStore;
use Application\DomainEvents\StoredEventFactory;
use Carbon\CarbonImmutable;
use Fakes\Application\DomainEvents\InstantaneousEvent;
use Fakes\Application\FakeClock;
use PHPUnit\Framework\TestCase;

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
    function let()
    {
        $this->store = $this->storeInstance();
        $factory = new StoredEventFactory(new JsonSerializer());

        $clock = new FakeClock(CarbonImmutable::parse('2019-12-25 22:53:00'));
        $event1 = $factory->from(new InstantaneousEvent($clock));
        $this->event2 = $factory->from(new InstantaneousEvent($clock));
        $event3 = $factory->from(new InstantaneousEvent($clock));
        $this->event4 = $factory->from(new InstantaneousEvent($clock));

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
