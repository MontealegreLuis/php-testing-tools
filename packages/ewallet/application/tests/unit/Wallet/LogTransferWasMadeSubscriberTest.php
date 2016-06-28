<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\{Accounts\MemberFormatter, DataBuilders\A};
use Hexagonal\DomainEvents\Event;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;

class LogTransferWasMadeSubscriberTest extends TestCase
{
    /** @test */
    function it_logs_when_a_transfer_has_been_made()
    {
        $logger = Mockery::spy(LoggerInterface::class);
        $subscriber = new TransferWasMadeLogger(
            $logger,
            new MemberFormatter()
        );
        $event = A::transferWasMadeEvent()->build();

        $subscriber->handle($event);

        $logger
            ->shouldHaveReceived('info')
            ->once()
            ->with(Mockery::type('string'))
        ;
    }

    /** @test */
    function it_logs_transfer_was_made_events_only()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $subscriber = new TransferWasMadeLogger(
            $logger,
            new MemberFormatter()
        );
        $event = A::transferWasMadeEvent()->build();


        $this->assertTrue($subscriber->isSubscribedTo($event));
    }

    /** @test */
    function it_does_not_log_events_other_than_the_transfer_was_made_event()
    {
        $event = Mockery::mock(Event::class);
        $subscriber = new TransferWasMadeLogger(
            Mockery::mock(LoggerInterface::class),
            new MemberFormatter()
        );

        $this->assertFalse($subscriber->isSubscribedTo($event));
    }
}
