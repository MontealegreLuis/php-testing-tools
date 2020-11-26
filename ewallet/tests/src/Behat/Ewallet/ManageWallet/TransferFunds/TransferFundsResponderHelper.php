<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Behat\Ewallet\ManageWallet\TransferFunds;

use Application\Actions\InputValidator;
use Ewallet\ManageWallet\TransferFunds\TransferFundsResponder;
use Ewallet\ManageWallet\TransferFunds\TransferFundsSummary;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\UnknownMember;
use Money\Money;
use PHPUnit\Framework\Assert;

class TransferFundsResponderHelper implements TransferFundsResponder
{
    private bool $transferWasMade = false;

    private bool $senderHasEnoughFunds = true;

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
        Assert::assertTrue($this->transferWasMade, 'Transfer is incomplete.');
    }

    public function assertBalanceIs(Money $expectedAmount, Member $forMember): void
    {
        Assert::assertTrue(
            $expectedAmount->equals($forMember->accountBalance()),
            sprintf(
                'Final balance does not match, expecting %.2f, found %.2f',
                $expectedAmount->getAmount() / 100,
                $forMember->accountBalance()->getAmount() / 100
            )
        );
    }

    public function respondToInsufficientFunds(InsufficientFunds $exception): void
    {
        $this->senderHasEnoughFunds = true;
    }

    public function assertSenderDoesNotHaveEnoughFunds(): void
    {
        Assert::assertTrue($this->senderHasEnoughFunds, 'Sender should not have enough funds.');
    }

    public function respondToInvalidInput(InputValidator $input): void
    {
        // Covered by TransferFundsTest
    }

    public function respondToUnknownMember(UnknownMember $exception): void
    {
        // Covered by TransferFundsTest
    }
}
