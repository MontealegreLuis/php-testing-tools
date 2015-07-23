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
    /** @var Account */
    private $account;

    /**
     * @param Account $account
     */
    private function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param Money $amount
     * @return Member
     */
    public static function withAccountBalance(Money $amount)
    {
        return new Member(Account::withBalance($amount));
    }

    /**
     * @return Money
     */
    public function balance()
    {
        return $this->account->balance();
    }

    /**
     * @param Money $amount
     * @param Member $toMember
     */
    public function transfer(Money $amount, Member $toMember)
    {
        $toMember->applyDeposit($amount);
        $this->account->withdraw($amount);
    }

    /**
     * @param Money $amount
     */
    protected function applyDeposit(Money $amount)
    {
        $this->account->deposit($amount);
    }
}
