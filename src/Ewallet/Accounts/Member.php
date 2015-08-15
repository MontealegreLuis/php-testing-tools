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

class Member implements CanRecordEvents
{
    use RecordsEvents;

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
     * @return MemberInformation
     */
    public function information()
    {
        return new MemberInformation($this->memberId, $this->name, $this->account);
    }

    /**
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
