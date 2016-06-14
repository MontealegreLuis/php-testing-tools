<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\{Member, MemberInformation};

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
    public function fromMember(): MemberInformation
    {
        return $this->fromMember->information();
    }

    /**
     * @return MemberInformation
     */
    public function toMember(): MemberInformation
    {
        return $this->toMember->information();
    }
}
