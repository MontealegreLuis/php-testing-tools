<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Ewallet\Memberships\Member;
use Ewallet\ManageWallet\{TransferFunds\CanTransferFunds, TransferFunds\TransferFundsSummary};
use Money\Money;

class TransferFundsHelper implements CanTransferFunds
{
    /** @var bool */
    private $transferWasMade = false;

    /**
     * Record that transfer was completed
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary): void
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
            $expectedAmount->equals($forMember->accountBalance()),
            sprintf(
                'Final balance does not match, expecting %.2f, found %.2f',
                $expectedAmount->getAmount() / 100,
                $forMember->accountBalance()->getAmount() / 100
            )
        );
    }

    public function respondToInvalidInput(\Ewallet\Application\Actions\InputValidator $input): void
    {
        // TODO: Implement respondToInvalidInput() method.
    }

    public function respondToUnknownMember(\Ewallet\Memberships\UnknownMember $exception): void
    {
        // TODO: Implement respondToUnknownMember() method.
    }

    public function respondToInsufficientFunds(\Ewallet\Memberships\InsufficientFunds $exception): void
    {
        // TODO: Implement respondToInsufficientFunds() method.
    }
}
