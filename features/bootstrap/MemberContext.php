<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Wallet\Accounts\InMemoryMembers;
use Ewallet\Wallet\TransferFunds;
use PHPUnit_Framework_Assert as Assertion;

/**
 * Defines application features from the specific context.
 */
class MemberContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /** @var Members */
    private $members;

    /** @var  TransferFunds */
    private $useCase;

    /**
     * Create an empty collection of members
     */
    public function __construct()
    {
        $this->members = new InMemoryMembers();
        $this->useCase = new TransferFunds($this->members);
    }

    /**
     * @Given I have an account balance of :amount MXN
     */
    public function iHaveAnAccountBalanceOfMxn($amount)
    {
        $me = Member::withAccountBalance(
            Identifier::fromString('abc'), $amount
        );
        $this->members->add($me);
    }

    /**
     * @Given my friend has an account balance of :amount MXN
     */
    public function myFriendHasAnAccountBalanceOfMxn($amount)
    {
        $myFriend = Member::withAccountBalance(
            Identifier::fromString('xyz'), $amount
        );
        $this->members->add($myFriend);
    }

    /**
     * @When I transfer him :amount MXN
     */
    public function iTransferHimMxn($amount)
    {
        $this->useCase->transfer(
            Identifier::fromString('abc'),
            Identifier::fromString('xyz'),
            $amount
        );
    }

    /**
     * @Then my balance should be :amount MXN
     */
    public function myBalanceShouldBeMxn($amount)
    {
        $my = $this->members->with(Identifier::fromString('abc'));
        $currentBalance = $my->accountBalance()->getAmount();
        Assertion::assertTrue(
            $my->accountBalance()->equals($amount),
            "Expecting {$amount->getAmount()}, not {$currentBalance}"
        );
    }

    /**
     * @Then my friend's balance should be :amount MXN
     */
    public function myFriendSBalanceShouldBeMxn($amount)
    {
        $myFriend = $this->members->with(Identifier::fromString('xyz'));
        $currentBalance = $myFriend->accountBalance()->getAmount();
        Assertion::assertTrue(
            $myFriend->accountBalance()->equals($amount),
            "Expecting {$amount->getAmount()}, not {$currentBalance}"
        );
    }
}
