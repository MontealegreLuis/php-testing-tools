<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Money\Money;
use RuntimeException;

/**
 * This exception is thrown when a member tries to transfer a negative amount.
 */
class InvalidTransfer extends RuntimeException
{
    /**
     * @param Money $amount
     * @return InvalidTransfer
     */
    public static function with(Money $amount): InvalidTransfer
    {
        return new self(
            "Cannot transfer a negative or zero amount {$amount->getAmount()}"
        );
    }
}
