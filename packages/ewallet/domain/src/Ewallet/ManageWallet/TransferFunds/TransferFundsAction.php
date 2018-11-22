<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\TransferFunds;

use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use Hexagonal\DomainEvents\PublishesEvents;
use LogicException;

/**
 * Command to transfer funds between a recipient and a sender
 */
class TransferFundsAction
{
    use PublishesEvents;

    /** @var Members */
    private $members;

    /** @var TransferFundsResponder */
    private $responder;

    public function __construct(Members $members)
    {
        $this->members = $members;
    }

    /**
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the recipient are unknown
     * @throws \Ewallet\Memberships\InsufficientFunds If the sender tries to transfer an amount greater than its current balance
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to transfer a negative amount
     * @throws LogicException If no action is attached to the current command
     */
    public function transfer(TransferFundsInput $input): void
    {
        if (!$input->isValid()) {
            $this->responder()->respondToInvalidInput($input);
            return;
        }
        $this->tryToTransferFunds($input);
    }

    private function tryToTransferFunds(TransferFundsInput $input): void
    {
        try {
            $sender = $this->members->with($input->senderId());
            $recipient = $this->members->with($input->recipientId());
        } catch (UnknownMember $exception) {
            $this->responder()->respondToUnknownMember($exception);
            return;
        }

        try {
            $sender->transfer($input->amount(), $recipient);
        } catch (InsufficientFunds $exception) {
            $this->responder()->respondToInsufficientFunds($exception);
            return;
        }

        $this->members->update($sender);
        $this->members->update($recipient);

        $this->publisher()->publish($sender->events());

        $this->responder()->respondToTransferCompleted(new TransferFundsSummary($sender, $recipient));
    }

    public function attach(TransferFundsResponder $responder): void
    {
        $this->responder = $responder;
    }

    /** @throws LogicException If no responder is attached to this action */
    private function responder(): TransferFundsResponder
    {
        if ($this->responder) {
            return $this->responder;
        }
        throw new LogicException('Cannot transfer funds without a responder');
    }
}
