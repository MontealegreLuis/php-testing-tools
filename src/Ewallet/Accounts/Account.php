<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Money\Money;

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
     * @param Money $amount
     * @return Account
     */
    public static function withBalance(Money $amount)
    {
        return new Account($amount);
    }

    /**
     * @return Money
     */
    public function balance()
    {
        return $this->balance;
    }

    /**
     * @param Money $amount
     */
    public function deposit(Money $amount)
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * @param Money $amount
     * @throws InsufficientFunds
     */
    public function withdraw(Money $amount)
    {
        if ($amount->greaterThan($this->balance)) {
            throw new InsufficientFunds("Cannot withdraw {$amount->getAmount()}");
        }
        $this->balance = $this->balance->subtract($amount);
    }
}
