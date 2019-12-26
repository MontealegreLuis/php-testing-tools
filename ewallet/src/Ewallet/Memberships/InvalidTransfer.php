<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Money\Money;
use RuntimeException;

/**
 * This exception is thrown when a member tries to transfer a negative amount.
 */
class InvalidTransfer extends RuntimeException
{
    public static function with(Money $amount): InvalidTransfer
    {
        return new self(sprintf(
            'Cannot transfer a negative or zero amount %.2f %s',
            $amount->getAmount(),
            $amount->getCurrency()->getCode()
        ));
    }
}
