<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Ewallet\Accounts\Member;
use Ewallet\Wallet\{CanTransferFunds, TransferFundsSummary};
use Money\Money;
use PHPUnit_Framework_Assert as Assertion;

class MembersHelper implements CanTransferFunds
{
    const TRANSFER_COMPLETED = 1;

    /** @var integer */
    private $lastEvent;

    /**
     * Record last event triggered
     *
     * @param TransferFundsSummary $summary
     */
    public function transferCompleted(TransferFundsSummary $summary)
    {
        $this->lastEvent = self::TRANSFER_COMPLETED;
    }

    /**
     * Assert that the 'Transfer completed' event is the last event triggered
     */
    public function assertTransferCompleted()
    {
        Assertion::assertEquals(self::TRANSFER_COMPLETED, $this->lastEvent);
    }

    /**
     * @param Money $amount
     * @param Member $forMember
     */
    public function assertBalanceIs(Money $amount, Member $forMember)
    {
        $currentBalance = $forMember->information()->accountBalance()->getAmount();
        Assertion::assertTrue(
            $forMember->information()->accountBalance()->equals($amount),
            "Expecting {$amount->getAmount()}, not {$currentBalance}"
        );
    }
}
