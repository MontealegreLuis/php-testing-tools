<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\Notifications;

use Ewallet\Memberships\{Members, TransferWasMade};

class TransferFundsEmailNotifier
{
    /** @var Members */
    private $members;

    /** @var TransferFundsEmailSender */
    private $sender;

    public function __construct(
        Members $members,
        TransferFundsEmailSender $sender
    ) {
        $this->members = $members;
        $this->sender = $sender;
    }

    public function shouldNotifyOn(string $event): bool
    {
        return TransferWasMade::class === $event;
    }

    /**
     * This event is handled after a successful funds transfer
     *
     * It will send an email to both members, summarizing their last account
     * transaction
     *
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the
     * recipient is unknown
     */
    public function notify(TransferFundsNotification $notification)
    {
        $sender = $this->members->with($notification->senderId());
        $recipient = $this->members->with($notification->recipientId());

        $this->sender->sendFundsTransferredEmail(
            $sender->information(),
            $recipient->information(),
            $notification->amount(),
            $notification->occurredOn()
        );

        $this->sender->sendDepositReceivedEmail(
            $sender->information(),
            $recipient->information(),
            $notification->amount(),
            $notification->occurredOn()
        );
    }
}
