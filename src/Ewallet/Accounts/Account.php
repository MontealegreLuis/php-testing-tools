<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

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
    public static function withBalance(Money $amount)
    {
        return new Account($amount);
    }

    /**
     * @return AccountInformation
     */
    public function information()
    {
        return new AccountInformation($this->balance);
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
            throw new InsufficientFunds("Cannot withdraw {$amount->getAmount()}");
        }
        $this->balance = $this->balance->subtract($amount);
    }
}
