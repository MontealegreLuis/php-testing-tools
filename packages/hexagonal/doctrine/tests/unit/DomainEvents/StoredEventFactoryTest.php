<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;
use Ewallet\Memberships\MemberId;
use Hexagonal\Fakes\DomainEvents\InstantaneousEvent;
use Hexagonal\JmsSerializer\JsonSerializer;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class StoredEventFactoryTest extends TestCase
{
    /** @test */
    function it_creates_an_stored_event_from_a_given_domain_event()
    {
        $event = new InstantaneousEvent(
            MemberId::with('abc'),
            Money::MXN(500000),
            new DateTime('2015-10-25 19:59:00')
        );
        $factory = new StoredEventFactory(new JsonSerializer());

        $storedEvent = $factory->from($event);

        // Stored events get an ID after being persisted
        $this->assertEquals(null, $storedEvent->id());
        $this->assertEquals(
            '{"occurred_on":"2015-10-25 19:59:00","member_id":"abc","amount":500000}',
            $storedEvent->body()
        );
        $this->assertEquals(InstantaneousEvent::class, $storedEvent->type());
        $this->assertEquals(
            '2015-10-25 19:59:00',
            $storedEvent->occurredOn()->format('Y-m-d H:i:s')
        );
    }
}
