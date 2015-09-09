<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;
use Hexagonal\DomainEvents\CanRecordEvents;
use Hexagonal\DomainEvents\RecordsEvents;
use Money\Money;

/**
 * Members can transfer money to each other's accounts
 */
class Member implements CanRecordEvents
{
    use RecordsEvents;

    /** @var Identifier */
    private $memberId;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var Account */
    private $account;

    /**
     * @param Identifier $id
     * @param string $name
     * @param Email $email
     * @param Account $account
     */
    private function __construct(
        Identifier $id,
        $name,
        Email $email,
        Account $account
    ) {
        $this->memberId = $id;
        $this->setName($name);
        $this->email = $email;
        $this->account = $account;
    }

    /**
     * A name cannot be empty
     *
     * @param string $name
     */
    protected function setName($name)
    {
        Assertion::string($name);
        Assertion::notEmpty($name);

        $this->name = $name;
    }

    /**
     * All members have an account with an initial balance
     *
     * @param Identifier $id
     * @param string $name
     * @param Email $email
     * @param Money $amount
     * @return Member
     */
    public static function withAccountBalance(
        Identifier $id,
        $name,
        Email $email,
        Money $amount
    ) {
        return new Member($id, $name, $email, Account::withBalance($amount));
    }

    /**
     * @return MemberInformation
     */
    public function information()
    {
        return new MemberInformation(
            $this->memberId,
            $this->name,
            $this->email,
            $this->account
        );
    }

    /**
     * Transfer a given amount to a specific member
     *
     * @param Money $amount
     * @param Member $toMember
     */
    public function transfer(Money $amount, Member $toMember)
    {
        $toMember->applyDeposit($amount);
        $this->account->withdraw($amount);
        $this->recordThat(
            new TransferWasMade($this->memberId, $amount, $toMember->memberId)
        );
    }

    /**
     * Deposit the given amount to the beneficiary's account
     *
     * @param Money $amount
     * @throws InvalidTransferAmount
     *     A member cannot transfer a negative amount to another member
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
