<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions\EventSubscribers;

use Ewallet\Accounts\Members;
use Ewallet\Accounts\TransferWasMade;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSubscriber;

class EmailTransferWasMadeSubscriber implements EventSubscriber
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
     * @param Event $event
     * @return boolean
     */
    public function isSubscribedTo(Event $event)
    {
        return TransferWasMade::class === get_class($event);
    }

    /**
     * This event is handled after a successful funds transfer
     *
     * It will send an email to both members, summarizing their last account
     * transaction
     *
     * @param Event $event
     * @return boolean
     */
    public function handle(Event $event)
    {
        $fromMember = $this->members->with($event->fromMemberId());
        $toMember = $this->members->with($event->toMemberId());

        $this->sender->sendFundsTransferredEmail(
            $fromMember->information(),
            $toMember->information(),
            $event->amount(),
            $event->occurredOn()
        );

        $this->sender->sendDepositReceivedEmail(
            $fromMember->information(),
            $toMember->information(),
            $event->amount(),
            $event->occurredOn()
        );
    }
}
