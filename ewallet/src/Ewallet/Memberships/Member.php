<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Application\DomainEvents\{CanRecordEvents, RecordsEvents};
use Money\Money;
use Webmozart\Assert\Assert;

/**
 * Members can transfer money to each other's accounts
 */
class Member implements CanRecordEvents
{
    use RecordsEvents;

    private MemberId $memberId;

    private string $name;

    private Email $email;

    private Account $account;

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
     * @throws InsufficientFunds If the sender tries to
     * transfer an amount greater than its current balance
     */
    public function transfer(Money $amount, Member $recipient): void
    {
        $recipient->receiveDeposit($amount);
        $this->account->withdraw($amount);
        $this->recordThat(new TransferWasMade($this->memberId, clone $amount, $recipient->memberId));
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

    public function emailAddress(): string
    {
        return $this->email->address();
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

    protected function setName(string $name): void
    {
        Assert::notEmpty($name);
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
