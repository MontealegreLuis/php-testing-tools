<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Member;
use Ewallet\Accounts\MemberInformation;

class TransferFundsResult
{
    /** @var Member */
    private $fromMember;

    /** @var Member */
    private $toMember;

    /**
     * @param Member $fromMember
     * @param Member $toMember
     */
    public function __construct(Member $fromMember, Member $toMember)
    {
        $this->fromMember = $fromMember;
        $this->toMember = $toMember;
    }

    /**
     * @return MemberInformation
     */
    public function fromMember()
    {
        return $this->fromMember->information();
    }

    /**
     * @return MemberInformation
     */
    public function toMember()
    {
        return $this->toMember->information();
    }
}
