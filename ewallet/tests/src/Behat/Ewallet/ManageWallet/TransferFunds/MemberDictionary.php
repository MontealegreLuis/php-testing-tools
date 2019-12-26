<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Behat\Ewallet\ManageWallet\TransferFunds;

use Money\Money;

trait MemberDictionary
{
    /** @Transform :amount */
    public function transformStringToMoney(string $amount): Money
    {
        return Money::MXN((int) $amount * 100);
    }
}
