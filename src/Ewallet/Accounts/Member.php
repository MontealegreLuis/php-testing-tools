<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Money\Money;

class Member
{
    /** @var Money */
    private $balance;

    /**
     * @param Money $balance
     */
    private function __construct(Money $balance)
    {
        $this->balance = $balance;
    }

    /**
     * @param Money $amount
     * @return Member
     */
    public static function withAccountBalance(Money $amount)
    {
        return new Member($amount);
    }

    /**
     * @return Money
     */
    public function balance()
    {
        return $this->balance;
    }
}
