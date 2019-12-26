<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\DomainEvents\EventPublisher;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use LogicException;

/**
 * Command to transfer funds between a recipient and a sender
 */
class TransferFundsAction
{
    /** @var Members */
    private $members;

    /** @var TransferFundsResponder|null */
    private $responder;

    /** @var EventPublisher */
    private $publisher;

    public function __construct(Members $members, EventPublisher $publisher)
    {
        $this->members = $members;
        $this->publisher = $publisher;
    }

    /** @throws LogicException If no action is attached to the current command */
    public function transfer(TransferFundsInput $input): void
    {
        try {
            $sender = $this->members->with($input->senderId());
            $recipient = $this->members->with($input->recipientId());
        } catch (UnknownMember $exception) {
            $this->responder()->respondToUnknownMember($exception);
            return;
        }

        $sender->transfer($input->amount(), $recipient);

        $this->members->update($sender);
        $this->members->update($recipient);

        $this->publisher->publish($sender->events());

        $this->responder()->respondToTransferCompleted(new TransferFundsSummary($sender, $recipient));
    }

    public function attach(TransferFundsResponder $responder): void
    {
        $this->responder = $responder;
    }

    /** @throws LogicException If no responder is attached to this action */
    protected function responder(): TransferFundsResponder
    {
        if ($this->responder !== null) {
            return $this->responder;
        }
        throw new LogicException('Cannot transfer funds without a responder');
    }
}
