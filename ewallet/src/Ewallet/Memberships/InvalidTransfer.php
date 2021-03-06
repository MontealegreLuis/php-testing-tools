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
 * This exception is thrown when a member tries to transfer a negative amount.
 */
final class InvalidTransfer extends DomainException
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
