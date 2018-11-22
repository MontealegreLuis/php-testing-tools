<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Assert\Assertion;
use Hexagonal\DomainEvents\{CanRecordEvents, RecordsEvents};
use Money\Money;

/**
 * Members can transfer money to each other's accounts
 */
class Member implements CanRecordEvents
{
    use RecordsEvents;

    /** @var MemberId */
    private $memberId;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var Account */
    private $account;

    /**
     * All members have an account with an initial balance
     */
    public static function withAccountBalance(
        MemberId $memberId,
        string $name,
        Email $email,
        Money $amount
    ): Member {
        return new Member($memberId, $name, $email, Account::withBalance($amount));
    }

    /**
     * Transfer a given amount to a specific member
     *
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to
     * transfer a negative amount
     * @throws \Ewallet\memberships\InsufficientFunds If the sender tries to
     * transfer an amount greater than its current balance
     */
    public function transfer(Money $amount, Member $recipient): void
    {
        $recipient->receiveDeposit($amount);
        $this->account->withdraw($amount);
        $this->recordThat(new TransferWasMade(
            $this->memberId, clone $amount, $recipient->memberId
        ));
    }

    public function equals(Member $anotherMember): bool
    {
        return $this->memberId->equals($anotherMember->memberId);
    }

    public function accountBalance(): Money
    {
        return clone $this->account->balance();
    }

    public function id(): MemberId
    {
        return $this->memberId;
    }

    public function idValue(): string
    {
        return $this->memberId->value();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hasId(MemberId $id): bool
    {
        return $this->memberId->equals($id);
    }

    private function __construct(
        MemberId $memberId,
        string $name,
        Email $email,
        Account $account
    ) {
        $this->memberId = $memberId;
        $this->setName(trim($name));
        $this->email = $email;
        $this->account = $account;
    }

    /**
     * @throws \Assert\AssertionFailedException If an empty name is given
     */
    protected function setName(string $name): void
    {
        Assertion::notEmpty($name, 'A member\'s name cannot be empty');

        $this->name = $name;
    }

    /**
     * Deposit the given amount to the recipient's account
     *
     * @throws InvalidTransfer A member cannot transfer a negative amount or 0 to another member
     */
    protected function receiveDeposit(Money $amount): void
    {
        if ($amount->isNegative() || $amount->isZero()) {
            throw InvalidTransfer::with($amount);
        }

        $this->account->deposit($amount);
    }
}
