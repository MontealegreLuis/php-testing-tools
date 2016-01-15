<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use DataBuilders\A;
use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\Accounts\InMemoryMembers;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsRequest;

/**
 * Defines application features from the specific context.
 */
class MemberContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /** @var Members */
    private $members;

    /** @var MembersHelper */
    private $membersHelper;

    /** @var  TransferFunds */
    private $useCase;

    /**
     * @BeforeScenario
     */
    public function prepare()
    {
        $this->members = new InMemoryMembers();
        $this->membersHelper = new MembersHelper();
        $this->useCase = new TransferFunds($this->members);
        $this->useCase->attach($this->membersHelper);
    }

    /**
     * @Given I have an account balance of :amount MXN
     */
    public function iHaveAnAccountBalanceOfMxn($amount)
    {
        $me = A::member()->withId('abc')->withBalance($amount)->build();

        $this->members->add($me);
    }

    /**
     * @Given my friend has an account balance of :amount MXN
     */
    public function myFriendHasAnAccountBalanceOfMxn($amount)
    {
        $myFriend = A::member()->withId('xyz')->withBalance($amount)->build();

        $this->members->add($myFriend);
    }

    /**
     * @When I transfer him :amount MXN
     */
    public function iTransferHimMxn($amount)
    {
        $this->useCase->transfer(TransferFundsRequest::from([
            'fromMemberId' => 'abc',
            'toMemberId' => 'xyz',
            'amount' => round($amount->getAmount() / 100),
        ]));
    }

    /**
     * @Then I should be notified that the transfer is complete
     */
    public function iShouldBeNotifiedThatTheTransferIsComplete()
    {
        $this->membersHelper->assertTransferCompleted();
    }

    /**
     * @Then my balance should be :amount MXN
     */
    public function myBalanceShouldBeMxn($amount)
    {
        $forMe = $this->members->with(Identifier::fromString('abc'));
        $this->membersHelper->assertBalanceIs($amount, $forMe);
    }

    /**
     * @Then my friend's balance should be :amount MXN
     */
    public function myFriendSBalanceShouldBeMxn($amount)
    {
        $forMyFriend = $this->members->with(Identifier::fromString('xyz'));
        $this->membersHelper->assertBalanceIs($amount, $forMyFriend);
    }
}
