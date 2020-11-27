<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Behat\Ewallet\ManageWallet\TransferFunds;

use Ewallet\Memberships\Member;
use Money\Money;
use PHPUnit\Framework\Assert;

final class TransferFundsResponderHelper
{
    private bool $senderHasEnoughFunds = true;

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

    public function assertSenderDoesNotHaveEnoughFunds(): void
    {
        Assert::assertTrue($this->senderHasEnoughFunds, 'Sender should not have enough funds.');
    }
}
