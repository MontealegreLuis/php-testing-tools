<?php
/**
 * PHP version 7.0
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

    /**
     * @param Money $amount
     */
    private function __construct(Money $amount)
    {
        $this->balance = $amount;
    }

    /**
     * Accounts can only be created with a given initial amount
     *
     * @param Money $amount
     * @return Account
     */
    public static function withBalance(Money $amount): Account
    {
        return new Account($amount);
    }

    /**
     * @return AccountInformation
     */
    public function information(): AccountInformation
    {
        return new AccountInformation(clone $this->balance);
    }

    /**
     * Increase this account current balance
     *
     * @param Money $amount
     */
    public function deposit(Money $amount)
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * Decrease this account current balance
     *
     * @param Money $amount
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
