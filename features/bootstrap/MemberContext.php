<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Ewallet\Accounts\Member;

/**
 * Defines application features from the specific context.
 */
class MemberContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /** @var Member */
    private $i;

    /** @var Member */
    private $myFriend;

    /**
     * @Given I have an account balance of :amount MXN
     */
    public function iHaveAnAccountBalanceOfMxn($amount)
    {
        $this->i = Member::withAccountBalance($amount);
    }

    /**
     * @Given my friend has an account balance of :amount MXN
     */
    public function myFriendHasAnAccountBalanceOfMxn($amount)
    {
        $this->myFriend = Member::withAccountBalance($amount);
    }

    /**
     * @When I transfer him :amount MXN
     */
    public function iTransferHimMxn($amount)
    {
        $this->i->transfer($amount, $this->myFriend);
    }

    /**
     * @Then my balance should be :amount MXN
     */
    public function myBalanceShouldBeMxn($amount)
    {
        if (!$this->i->balance()->equals($amount)) {
            $currentBalance = $this->i->balance()->getAmount();
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
        if (!$this->myFriend->balance()->equals($amount)) {
            $currentBalance = $this->myFriend->balance()->getAmount();
            throw new DomainException(
                "Expecting {$amount->getAmount()}, not {$currentBalance}"
            );
        }
    }
}
