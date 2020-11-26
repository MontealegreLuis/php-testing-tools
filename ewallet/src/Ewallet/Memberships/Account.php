<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Money\Money;

/**
 * Every member has an account with an initial balance.
 */
final class Account
{
    private Money $balance;

    private function __construct(Money $amount)
    {
        $this->balance = $amount;
    }

    /**
     * Accounts can only be created with a given initial amount
     */
    public static function withBalance(Money $amount): Account
    {
        return new Account($amount);
    }

    public function balance(): Money
    {
        return clone $this->balance;
    }

    /**
     * Increase this account current balance
     */
    public function deposit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * Decrease this account current balance
     *
     * @throws InsufficientFunds
     *     A member cannot withdraw more than it's account current balance
     */
    public function withdraw(Money $amount): void
    {
        if ($amount->greaterThan($this->balance)) {
            throw InsufficientFunds::withdrawing($amount, $this->balance);
        }
        $this->balance = $this->balance->subtract($amount);
    }
}
