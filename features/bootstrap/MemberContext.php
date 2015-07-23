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

/**
 * Defines application features from the specific context.
 */
class MemberContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /** @var Members */
    private $members;

    /**
     * Create an empty collection of members
     */
    public function __construct()
    {
        $this->members = new InMemoryMembers();
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
        $i = $this->members->with(Identifier::fromString('abc'));
        $myFriend = $this->members->with(Identifier::fromString('xyz'));

        $i->transfer($amount, $myFriend);

        $this->members->update($i);
        $this->members->update($myFriend);
    }

    /**
     * @Then my balance should be :amount MXN
     */
    public function myBalanceShouldBeMxn($amount)
    {
        $my = $this->members->with(Identifier::fromString('abc'));
        if (!$my->accountBalance()->equals($amount)) {
            $currentBalance = $my->accountBalance()->getAmount();
            throw new DomainException(
                "Expecting {$amount->getAmount()}, not {$currentBalance}"
            );
        }
    }

    /**
     * @Then my friend's balance should be :amount MXN
     */
    public function myFriendSBalanceShouldBeMxn($amount)
    {
        $myFriend = $this->members->with(Identifier::fromString('xyz'));
        if (!$myFriend->accountBalance()->equals($amount)) {
            $currentBalance = $myFriend->accountBalance()->getAmount();
            throw new DomainException(
                "Expecting {$amount->getAmount()}, not {$currentBalance}"
            );
        }
    }
}
