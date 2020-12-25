<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Application\DomainException;
use Money\Money;

/**
 * This exception is thrown to prevent a member to overdraw her account
 */
final class InsufficientFunds extends DomainException
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
