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
    /** @var Identifier */
    private $memberId;

    /** @var Account */
    private $account;

    /**
     * @param Identifier $id
     * @param Account $account
     */
    private function __construct(Identifier $id, Account $account)
    {
        $this->account = $account;
        $this->memberId = $id;
    }

    /**
     * @param Identifier $id
     * @param Money $amount
     * @return Member
     */
    public static function withAccountBalance(Identifier $id, Money $amount)
    {
        return new Member($id, Account::withBalance($amount));
    }

    /**
     * @return Money
     */
    public function accountBalance()
    {
        return $this->account->balance();
    }

    /**
     * @return Identifier
     */
    public function id()
    {
        return $this->memberId;
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
