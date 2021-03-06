<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;

/**
 * Contains the summary of balances for both the recipient and the sender
 */
final class TransferFundsSummary
{
    private Member $sender;

    private Member $recipient;

    public function __construct(Member $sender, Member $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function sender(): Member
    {
        return $this->sender;
    }

    public function recipient(): Member
    {
        return $this->recipient;
    }

    public function senderId(): MemberId
    {
        return $this->sender->id();
    }

    public function recipientId(): MemberId
    {
        return $this->recipient->id();
    }
}
