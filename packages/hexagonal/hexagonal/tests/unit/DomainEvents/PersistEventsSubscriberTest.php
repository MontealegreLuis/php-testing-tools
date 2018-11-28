<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;
use Ewallet\{DataBuilders\A, Memberships\MemberId};
use Hexagonal\Fakes\DomainEvents\InstantaneousEvent;
use Ports\JmsSerializer\DomainEvents\JsonSerializer;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PersistEventsSubscriberTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    function it_subscribes_to_any_event_type()
    {
        $instantaneousEvent = new InstantaneousEvent(
            MemberId::withIdentity('any'), Money::MXN(100000), new DateTime('now')
        );
        $transferWasMadeEvent = A::transferWasMadeEvent()->build();

        $this->assertTrue($this->subscriber->isSubscribedTo($instantaneousEvent));
        $this->assertTrue($this->subscriber->isSubscribedTo($transferWasMadeEvent));
    }

    /** @test */
    function it_persists_an_event()
    {
        $this->subscriber->handle(A::transferWasMadeEvent()->build());

        $this
            ->store
            ->shouldHaveReceived('append')
            ->once()
            ->with(Mockery::type(StoredEvent::class))
        ;
    }

    /** @before */
    function configureSubscriber(): void
    {
        $this->store = Mockery::spy(EventStore::class);
        $this->subscriber = new PersistEventsSubscriber(
            $this->store,
            new StoredEventFactory(new JsonSerializer())
        );
    }

    /** @var PersistEventsSubscriber */
    private $subscriber;

    /** @var EventStore */
    private $store;
}
