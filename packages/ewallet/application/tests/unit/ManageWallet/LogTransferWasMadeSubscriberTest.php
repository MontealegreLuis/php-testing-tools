<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\{Memberships\MemberFormatter, DataBuilders\A};
use Hexagonal\DomainEvents\Event;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;

class LogTransferWasMadeSubscriberTest extends TestCase
{
    /** @test */
    function it_logs_when_a_transfer_has_been_made()
    {
        $event = A::transferWasMadeEvent()->build();

        $this->subscriber->handle($event);

        $this->logger
            ->shouldHaveReceived('info')
            ->once()
            ->with(Mockery::type('string'))
        ;
    }

    /** @test */
    function it_is_subscribed_to_transfer_was_made_events_only()
    {
        $event = A::transferWasMadeEvent()->build();


        $this->assertTrue($this->subscriber->isSubscribedTo($event));
    }

    /** @test */
    function it_is_not_subscribed_to_events_other_than_the_transfer_was_made_event()
    {
        $event = Mockery::mock(Event::class);

        $this->assertFalse($this->subscriber->isSubscribedTo($event));
    }

    /** @before */
    public function configureSubscriber()
    {
        $this->logger = Mockery::spy(LoggerInterface::class);
        $this->subscriber = new TransferWasMadeLogger(
            $this->logger,
            new MemberFormatter()
        );
    }

    /** @var TransferWasMadeLogger */
    private $subscriber;

    /** @var LoggerInterface */
    private $logger;
}
