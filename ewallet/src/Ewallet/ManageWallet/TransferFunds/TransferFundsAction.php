<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\DomainEvents\EventPublisher;
use Ewallet\Memberships\Members;

/**
 * Command to transfer funds between a recipient and a sender
 */
class TransferFundsAction
{
    private Members $members;
    private EventPublisher $publisher;

    public function __construct(Members $members, EventPublisher $publisher)
    {
        $this->members = $members;
        $this->publisher = $publisher;
    }

    public function transfer(TransferFundsInput $input): TransferFundsSummary
    {
        $sender = $this->members->with($input->senderId());
        $recipient = $this->members->with($input->recipientId());

        $sender->transfer($input->amount(), $recipient);

        $this->members->save($sender);
        $this->members->save($recipient);

        $this->publisher->publish($sender->events());

        return new TransferFundsSummary($sender, $recipient);
    }
}
