<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Ewallet\Accounts\Member;

/**
 * Defines application features from the specific context.
 */
class MemberContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /**
     * @Given I have an account balance of :amount MXN
     */
    public function iHaveAnAccountBalanceOfMxn($amount)
    {
        $i = Member::withAccountBalance($amount);
    }

    /**
     * @Given my friend has an account balance of :amount MXN
     */
    public function myFriendHasAnAccountBalanceOfMxn($amount)
    {
        throw new PendingException();
    }

    /**
     * @When I transfer him :amount MXN
     */
    public function iTransferHimMxn($amount)
    {
        throw new PendingException();
    }

    /**
     * @Then my balance should be :amount MXN
     */
    public function myBalanceShouldBeMxn($amount)
    {
        throw new PendingException();
    }

    /**
     * @Then my friend's balance should be :amount MXN
     */
    public function myFriendSBalanceShouldBeMxn($amount)
    {
        throw new PendingException();
    }
}
