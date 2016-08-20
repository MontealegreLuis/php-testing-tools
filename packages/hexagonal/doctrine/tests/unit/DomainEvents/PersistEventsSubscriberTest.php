<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;
use Ewallet\{DataBuilders\A, Memberships\MemberId};
use Hexagonal\Fakes\DomainEvents\InstantaneousEvent;
use Hexagonal\JmsSerializer\JsonSerializer;
use Mockery;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class PersistEventsSubscriberTest extends TestCase
{
    /** @test */
    function it_subscribes_to_all_event_types()
    {
        $subscriber = new PersistEventsSubscriber(
            Mockery::mock(EventStore::class),
            Mockery::mock(StoredEventFactory::class)
        );

        $this->assertTrue($subscriber->isSubscribedTo(new InstantaneousEvent(
            MemberId::withIdentity('any'), Money::MXN(100000), new DateTime('now')
        )));

        $this->assertTrue($subscriber->isSubscribedTo(
            A::transferWasMadeEvent()->build())
        );
    }

    /** @test */
    function it_persists_an_event()
    {
        $store = Mockery::mock(EventStore::class);
        $subscriber = new PersistEventsSubscriber(
            $store,
            new StoredEventFactory(new JsonSerializer())
        );

        $store
            ->shouldReceive('append')
            ->once()
            ->with(Mockery::type(StoredEvent::class))
        ;

        $subscriber->handle(A::transferWasMadeEvent()->build());
    }
}
