<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;
use Money\Money;

class Member
{
    /** @var Identifier */
    private $memberId;

    /** @var string */
    private $name;

    /** @var Account */
    private $account;

    /**
     * @param Identifier $id
     * @param string $name
     * @param Account $account
     */
    private function __construct(Identifier $id, $name, Account $account)
    {
        $this->memberId = $id;
        $this->setName($name);
        $this->account = $account;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        Assertion::string($name);
        Assertion::notEmpty($name);

        $this->name = $name;
    }

    /**
     * @param Identifier $id
     * @param string $name
     * @param Money $amount
     * @return Member
     */
    public static function withAccountBalance(Identifier $id, $name, Money $amount)
    {
        return new Member($id, $name, Account::withBalance($amount));
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
     * @return string
     */
    public function name()
    {
        return $this->name;
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
        if ($amount->isNegative()) {
            throw new InvalidTransferAmount(
                "Cannot transfer negative amount {$amount->getAmount()}"
            );
        }

        $this->account->deposit($amount);
    }
}
