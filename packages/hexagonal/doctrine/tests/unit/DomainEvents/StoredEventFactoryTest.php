<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;
use Ewallet\Memberships\MemberId;
use Hexagonal\Fakes\DomainEvents\InstantaneousEvent;
use Hexagonal\JmsSerializer\JsonSerializer;
use Money\Money;
use PHPUnit\Framework\TestCase;

class StoredEventFactoryTest extends TestCase
{
    /** @test */
    function it_creates_an_stored_event_from_a_given_domain_event()
    {
        $memberId = 'abc';
        $amountInCents = 500000;
        $occurredOnDate = '2015-10-25 19:59:00';
        $event = new InstantaneousEvent(
            MemberId::withIdentity($memberId),
            Money::MXN($amountInCents),
            new DateTime($occurredOnDate)
        );

        $storedEvent = $this->factory->from($event);

        // Stored events get an identifier ONLY AFTER being persisted
        $this->assertEquals(0, $storedEvent->id());
        $this->assertEquals(
            "{\"occurred_on\":\"$occurredOnDate\",\"member_id\":\"$memberId\",\"amount\":\"$amountInCents\"}",
            $storedEvent->body()
        );
        $this->assertEquals(InstantaneousEvent::class, $storedEvent->type());
        $this->assertEquals(
            $occurredOnDate,
            $storedEvent->occurredOn()->format('Y-m-d H:i:s')
        );
    }

    /** @before */
    function configureFactory(): void
    {
        $this->factory = new StoredEventFactory(new JsonSerializer());
    }

    /** @var StoredEventFactory */
    private $factory;
}
