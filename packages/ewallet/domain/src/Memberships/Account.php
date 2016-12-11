<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Money\Money;

/**
 * Every member has an account with an initial balance.
 */
class Account
{
    /** @var Money */
    private $balance;

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

    public function information(): AccountInformation
    {
        return new AccountInformation(clone $this->balance);
    }

    /**
     * Increase this account current balance
     */
    public function deposit(Money $amount)
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * Decrease this account current balance
     *
     * @throws InsufficientFunds
     *     A member cannot withdraw more than it's account current balance
     */
    public function withdraw(Money $amount)
    {
        if ($amount->greaterThan($this->balance)) {
            throw InsufficientFunds::withdrawing($amount, $this->balance);
        }
        $this->balance = $this->balance->subtract($amount);
    }
}
