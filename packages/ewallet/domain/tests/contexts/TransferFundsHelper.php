<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Ewallet\Memberships\Member;
use Ewallet\ManageWallet\{CanTransferFunds, TransferFundsSummary};
use Money\Money;

class TransferFundsHelper implements CanTransferFunds
{
    /** @var bool */
    private $transferWasMade = false;

    /**
     * Record that transfer was completed
     */
    public function transferCompleted(TransferFundsSummary $summary): void
    {
        $this->transferWasMade = true;
    }

    /**
     * Assert that the 'Transfer was made' event is the last event triggered
     */
    public function assertTransferWasMade(): void
    {
        assertTrue($this->transferWasMade, 'Transfer is incomplete.');
    }

    public function assertBalanceIs(
        Money $expectedAmount,
        Member $forMember
    ): void
    {
        assertTrue(
            $expectedAmount->equals($forMember->information()->accountBalance()),
            sprintf(
                'Final balance does not match, expecting %.2f, found %.2f',
                $expectedAmount->getAmount() / 100,
                $forMember->information()->accountBalance()->getAmount() / 100
            )
        );
    }
}
