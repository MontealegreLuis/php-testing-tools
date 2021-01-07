<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use Ewallet\Memberships\Members;
use Ewallet\Memberships\TransferWasMade;
use Ewallet\Memberships\UnknownMember;

/** @noRector Rector\SOLID\Rector\Class_\FinalizeClassesWithoutChildrenRector */
class TransferFundsEmailNotifier
{
    private Members $members;

    private TransferFundsEmailSender $sender;

    public function __construct(Members $members, TransferFundsEmailSender $sender)
    {
        $this->members = $members;
        $this->sender = $sender;
    }

    public function shouldNotifyOn(string $event): bool
    {
        return $event === TransferWasMade::class;
    }

    /**
     * This event is handled after a successful funds transfer
     *
     * It will send an email to both members summarizing their last account
     * transaction
     *
     * @throws UnknownMember If either the sender or the
     * recipient is unknown
     */
    public function notify(TransferFundsNotification $notification): void
    {
        $sender = $this->members->with($notification->senderId());
        $recipient = $this->members->with($notification->recipientId());

        $this->sender->sendFundsTransferredEmail(
            $sender,
            $recipient,
            $notification->amount(),
            $notification->occurredOn()
        );

        $this->sender->sendDepositReceivedEmail(
            $sender,
            $recipient,
            $notification->amount(),
            $notification->occurredOn()
        );
    }
}
