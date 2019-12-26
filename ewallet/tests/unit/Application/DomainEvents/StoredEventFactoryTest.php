<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Carbon\CarbonImmutable;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\TransferWasMade;
use Fakes\Application\FakeClock;
use Money\Money;
use PHPUnit\Framework\TestCase;

class StoredEventFactoryTest extends TestCase
{
    /** @test */
    function it_creates_an_stored_event_from_a_given_domain_event()
    {
        $occurredOn = CarbonImmutable::parse('2015-10-25 19:59:00');
        $clock = new FakeClock($occurredOn);

        $storedEvent = $this->factory->from(new TransferWasMade(
            new MemberId('abc'),
            Money::MXN(500000),
            new MemberId('xyz'),
            $clock
        ));

        $this->assertEquals(
            '{"occurred_on":"2015-10-25 19:59:00","sender_id":"abc","amount":"500000","recipient_id":"xyz"}',
            $storedEvent->body()
        );
        $this->assertEquals(TransferWasMade::class, $storedEvent->type());
        $this->assertEquals($occurredOn, $storedEvent->occurredOn());
    }

    /** @before */
    function configureFactory(): void
    {
        $this->factory = new StoredEventFactory(new JsonSerializer());
    }

    /** @var StoredEventFactory */
    private $factory;
}
