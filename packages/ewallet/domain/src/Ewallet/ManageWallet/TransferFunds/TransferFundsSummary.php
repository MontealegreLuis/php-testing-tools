<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\TransferFunds;

use Ewallet\Memberships\{Member, MemberInformation};

/**
 * Contains the summary of balances for both the recipient and the sender
 */
final class TransferFundsSummary
{
    /** @var Member */
    private $sender;

    /** @var Member */
    private $recipient;

    public function __construct(Member $sender, Member $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function sender(): MemberInformation
    {
        return $this->sender->information();
    }

    public function recipient(): MemberInformation
    {
        return $this->recipient->information();
    }
}
