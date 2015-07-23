<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Members;
use Money\Money;

class TransferFunds
{
    /** @var Members */
    private $members;

    /**
     * @param Members $members
     */
    public function __construct(Members $members)
    {
        $this->members = $members;
    }

    /**
     * @param Identifier $fromMemberId
     * @param Identifier $toMemberId
     * @param Money $amount
     * @return TransferFundsResult
     */
    public function transfer(
        Identifier $fromMemberId, Identifier $toMemberId, Money $amount
    ) {
        $fromMember = $this->members->with($fromMemberId);
        $toMember = $this->members->with($toMemberId);

        $fromMember->transfer($amount, $toMember);

        $this->members->update($fromMember);
        $this->members->update($toMember);

        return new TransferFundsResult($fromMember, $toMember);
    }
}
