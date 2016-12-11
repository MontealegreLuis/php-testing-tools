<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\Members;
use Hexagonal\DomainEvents\PublishesEvents;
use LogicException;

/**
 * Command to transfer funds between a recipient and a sender
 */
class TransferFunds
{
    use PublishesEvents;

    /** @var Members */
    private $members;

    /** @var CanTransferFunds */
    private $action;

    public function __construct(Members $members)
    {
        $this->members = $members;
    }

    public function attach(CanTransferFunds $action)
    {
        $this->action = $action;
    }

    /**
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the
     * recipient are unknown
     * @throws \Ewallet\Memberships\InsufficientFunds If the sender tries to
     * transfer an amount greater than its current balance
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to
     * transfer a negative amount
     * @throws LogicException If no action is attached to the current command
     */
    public function transfer(TransferFundsInformation $information)
    {
        $sender = $this->members->with($information->senderId());
        $recipient = $this->members->with($information->recipientId());

        $sender->transfer($information->amount(), $recipient);

        $this->members->update($sender);
        $this->members->update($recipient);

        $this->publisher()->publish($sender->events());

        $this->action()->transferCompleted(new TransferFundsSummary(
            $sender, $recipient
        ));
    }

    /**
     * @throws LogicException If no action is attached to this command
     */
    private function action(): CanTransferFunds
    {
        if ($this->action) {
            return $this->action;
        }
        throw new LogicException('No action was attached');
    }
}
