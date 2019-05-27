<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Money\Money;
use RuntimeException;

/**
 * This exception is thrown to prevent a member to overdraw her account
 */
class InsufficientFunds extends RuntimeException
{
    public static function withdrawing(
        Money $amount,
        Money $currentBalance
    ): InsufficientFunds {
        return new self(sprintf(
            'Cannot withdraw %.2f %s, current balance is %.2f %s',
            $amount->getAmount() / 100,
            $amount->getCurrency()->getCode(),
            $currentBalance->getAmount() / 100,
            $currentBalance->getCurrency()->getCode()
        ));
    }
}
