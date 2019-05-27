<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use DataBuilders\A;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\TransferWasMade;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class TransferFundsEmailNotifierTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    function it_only_notifies_on_transfer_was_made_events()
    {
        $this->assertTrue(
            $this->notifier->shouldNotifyOn(TransferWasMade::class)
        );
    }

    /** @test */
    function it_ignores_other_events()
    {
        $this->assertFalse(
            $this->notifier->shouldNotifyOn('Ewallet\Accounts\AccountOverdrawn')
        );
    }

    /** @test */
    function it_sends_notification_emails_when_a_transfer_is_made()
    {
        $senderId = 'abc';
        $recipientId = 'xyz';
        $notification = new TransferFundsNotification(
            $senderId,
            500,
            $recipientId,
            '2016-08-15 00:00:00'
        );
        $this->members
            ->shouldReceive('with')
            ->with($notification->senderId())
            ->andReturn(A::member()->withId($senderId)->build())
        ;
        $this->members
            ->shouldReceive('with')
            ->with($notification->recipientId())
            ->andReturn(A::member()->withId($recipientId)->build())
        ;

        $this->notifier->notify($notification);

        $this->sender
            ->shouldHaveReceived('sendFundsTransferredEmail')
            ->once()
        ;
        $this->sender
            ->shouldHaveReceived('sendDepositReceivedEmail')
            ->once()
        ;
    }

    /** @before */
    public function configureNotifier()
    {
        $this->sender = Mockery::spy(TransferFundsEmailSender::class);
        $this->members = Mockery::mock(Members::class);
        $this->notifier = new TransferFundsEmailNotifier(
            $this->members,
            $this->sender
        );
    }

    /** @var TransferFundsEmailNotifier */
    private $notifier;

    /** @var Members */
    private $members;

    /** @var TransferFundsEmailSender */
    private $sender;
}
