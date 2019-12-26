<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Application\Clock;
use DataBuilders\A;
use Fakes\Application\DomainEvents\InstantaneousEvent;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class PersistEventsSubscriberTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    function it_subscribes_to_any_event_type()
    {
        $instantaneousEvent = new InstantaneousEvent(Clock::now());
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
