<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet\Notifications;

use Ewallet\Accounts\{Members, TransferWasMade};
use Ewallet\DataBuilders\A;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsEmailNotifierTest extends TestCase
{
    /** @test */
    function it_only_notifies_on_transfer_was_made_events()
    {
        $sender = Mockery::mock(TransferFundsEmailSender::class);
        $members = Mockery::mock(Members::class);
        $notifier = new TransferFundsEmailNotifier($members, $sender);

        $notifies = $notifier->shouldNotifyOn(TransferWasMade::class);

        $this->assertTrue($notifies);
    }

    /** @test */
    function it_ignores_other_events()
    {
        $sender = Mockery::mock(TransferFundsEmailSender::class);
        $members = Mockery::mock(Members::class);
        $notifier = new TransferFundsEmailNotifier($members, $sender);

        $notifies = $notifier->shouldNotifyOn('Ewallet\Accounts\AccountOverdrawn');

        $this->assertFalse($notifies);
    }

    /** @test */
    function it_sends_notification_emails_when_a_transfer_is_made()
    {
        $senderId = 'abc';
        $recipientId = 'xyz';
        $notification = new TransferFundsNotification(
            $senderId, 500, $recipientId, '2016-08-15 00:00:00'
        );
        $sender = Mockery::spy(TransferFundsEmailSender::class);
        $members = Mockery::mock(Members::class);
        $members
            ->shouldReceive('with')
            ->with($notification->senderId())
            ->andReturn(A::member()->withId($senderId)->build())
        ;
        $members
            ->shouldReceive('with')
            ->with($notification->recipientId())
            ->andReturn(A::member()->withId($recipientId)->build())
        ;
        $notifier = new TransferFundsEmailNotifier($members, $sender);

        $notifier->notify($notification);

        $sender
            ->shouldHaveReceived('sendFundsTransferredEmail')
            ->once()
        ;
        $sender
            ->shouldHaveReceived('sendDepositReceivedEmail')
            ->once()
        ;
    }
}
