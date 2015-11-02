<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use Ewallet\Bridges\Tests\A;
use Hexagonal\Bridges\JmsSerializer\JsonSerializer;
use PHPUnit_Framework_TestCase as TestCase;

abstract class EventStoreTest extends TestCase
{
    /** @var EventStore */
    private $store;

    /** @var StoredEvent */
    private $event2;

    /** @var StoredEvent */
    private $event4;

    /** @before */
    function generateFixtures()
    {
        $this->store = $this->storeInstance();
        $factory = new StoredEventFactory(new JsonSerializer());

        $event1 = $factory->from(A::transferWasMadeEvent()->build());
        $this->event2 = $factory->from(A::transferWasMadeEvent()->build());
        $event3 = $factory->from(A::transferWasMadeEvent()->build());
        $this->event4 = $factory->from(A::transferWasMadeEvent()->build());

        $this->store->append($event1);
        $this->store->append($this->event2);
        $this->store->append($event3);
        $this->store->append($this->event4);
    }

    /** @test */
    function it_should_retrieve_all_stored_events()
    {
        $events = $this->store->allEvents();

        $this->assertCount(4, $events);
    }

    /** @test */
    function it_should_retrieve_2_out_of_4_events_if_second_event_id_is_provided()
    {
        $events = $this->store->eventsStoredAfter($this->event2->id());

        $this->assertCount(2, $events);
    }

    /** @test */
    function it_should_retrieve_0_events_if_last_event_id_is_provided()
    {
        $events = $this->store->eventsStoredAfter($this->event4->id());

        $this->assertCount(0, $events);
    }

    /**
     * @return EventStore
     */
    abstract function storeInstance();
}
