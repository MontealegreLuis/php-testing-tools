<?php
/**
 * PHP version 7.0
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
     * @param MemberId $memberId
     * @param string $name
     * @param Email $email
     * @param Account $account
     */
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
     * A name cannot be empty
     *
     * @param string $name
     */
    protected function setName(string $name)
    {
        Assertion::notEmpty($name, 'A member\'s name cannot be empty');

        $this->name = $name;
    }

    /**
     * All members have an account with an initial balance
     *
     * @param MemberId $memberId
     * @param string $name
     * @param Email $email
     * @param Money $amount
     * @return Member
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
     * @return MemberInformation
     */
    public function information(): MemberInformation
    {
        return new MemberInformation(
            $this->memberId,
            $this->name,
            $this->email,
            $this->account->information()
        );
    }

    /**
     * Transfer a given amount to a specific member
     *
     * @param Money $amount
     * @param Member $recipient
     */
    public function transfer(Money $amount, Member $recipient)
    {
        $recipient->receiveDeposit($amount);
        $this->account->withdraw($amount);
        $this->recordThat(new TransferWasMade(
            $this->memberId, clone $amount, $recipient->memberId
        ));
    }

    /**
     * Deposit the given amount to the beneficiary's account
     *
     * @param Money $amount
     * @throws InvalidTransfer
     *     A member cannot transfer a negative amount to another member
     */
    protected function receiveDeposit(Money $amount)
    {
        if ($amount->isNegative() || $amount->isZero()) {
            throw InvalidTransfer::with($amount);
        }

        $this->account->deposit($amount);
    }

    /**
     * @param Member $anotherMember
     * @return bool
     */
    public function equals(Member $anotherMember): bool
    {
        return $this->memberId->equals($anotherMember->memberId);
    }
}
