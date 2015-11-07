<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions\Notifications;

use Ewallet\Accounts\Members;
use Ewallet\Accounts\TransferWasMade;

class TransferFundsEmailNotifier
{
    /** @var Members */
    private $members;

    /** @var TransferFundsEmailSender */
    private $sender;

    /**
     * @param Members $members
     * @param TransferFundsEmailSender $sender
     */
    public function __construct(
        Members $members,
        TransferFundsEmailSender $sender
    ) {
        $this->members = $members;
        $this->sender = $sender;
    }

    /**
     * @param string $event
     * @return boolean
     */
    public function shouldNotifyOn($event)
    {
        return TransferWasMade::class === $event;
    }

    /**
     * This event is handled after a successful funds transfer
     *
     * It will send an email to both members, summarizing their last account
     * transaction
     *
     * @param TransferFundsNotification $notification
     * @return bool
     */
    public function notify(TransferFundsNotification $notification)
    {
        $fromMember = $this->members->with($notification->fromMemberId());
        $toMember = $this->members->with($notification->toMemberId());

        $this->sender->sendFundsTransferredEmail(
            $fromMember->information(),
            $toMember->information(),
            $notification->amount(),
            $notification->occurredOn()
        );

        $this->sender->sendDepositReceivedEmail(
            $fromMember->information(),
            $toMember->information(),
            $notification->amount(),
            $notification->occurredOn()
        );
    }
}
