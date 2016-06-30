<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\{Member, MemberInformation};

class TransferFundsSummary
{
    /** @var Member */
    private $sender;

    /** @var Member */
    private $recipient;

    /**
     * @param Member $sender
     * @param Member $recipient
     */
    public function __construct(Member $sender, Member $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /**
     * @return MemberInformation
     */
    public function sender(): MemberInformation
    {
        return $this->sender->information();
    }

    /**
     * @return MemberInformation
     */
    public function recipient(): MemberInformation
    {
        return $this->recipient->information();
    }
}
