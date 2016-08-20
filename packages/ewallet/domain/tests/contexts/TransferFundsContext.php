<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Behat\Behat\Context\{Context, SnippetAcceptingContext};
use Ewallet\Accounts\{InMemoryMembers, MemberId};
use Ewallet\DataBuilders\A;
use Ewallet\Wallet\{TransferFunds, TransferFundsInformation};
use Money\Money;

/**
 * Defines application features from the specific context.
 */
class TransferFundsContext implements Context, SnippetAcceptingContext
{
    use MemberDictionary;

    /** @var string */
    private $senderId = 'abc';

    /** @var string */
    private $recipientId = 'xyz';

    /** @var \Ewallet\Accounts\Members */
    private $members;

    /** @var TransferFundsHelper */
    private $helper;

    /** @var TransferFunds */
    private $useCase;

    /**
     * @BeforeScenario
     */
    public function prepare()
    {
        $this->members = new InMemoryMembers();
        $this->helper = new TransferFundsHelper();
        $this->useCase = new TransferFunds($this->members);
        $this->useCase->attach($this->helper);
    }

    /**
     * @Given a sender with an account balance of :amount MXN
     */
    public function aSenderWithAnAccountBalanceOfMxn(Money $amount)
    {
        $sender = A::member()->withId($this->senderId)->withBalance($amount)->build();

        $this->members->add($sender);
    }

    /**
     * @Given a recipient with an account balance of :amount MXN
     */
    public function aRecipientWithAnAccountBalanceOfMxn(Money $amount)
    {
        $recipient = A::member()->withId($this->recipientId)->withBalance($amount)->build();

        $this->members->add($recipient);
    }

    /**
     * @When the sender transfers :amount MXN to the recipient
     */
    public function theSenderTransfersMxnToTheRecipient(Money $amount)
    {
        $this->useCase->transfer(TransferFundsInformation::from([
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId,
            'amount' => round($amount->getAmount() / 100),
        ]));
    }

    /**
     * @Then the sender is notified that the transfer is complete
     */
    public function theSenderIsNotifiedThatTheTransferIsComplete()
    {
        $this->helper->assertTransferWasMade();
    }

    /**
     * @Then the sender's balance should be :amount MXN
     */
    public function theSendersBalanceShouldBeMxn(Money $amount)
    {
        $sender = $this->members->with(MemberId::with($this->senderId));
        $this->helper->assertBalanceIs($amount, $sender);
    }

    /**
     * @Then the recipient's balance should be :amount MXN
     */
    public function theRecipientsBalanceShouldBeMxn(Money $amount)
    {
        $recipient = $this->members->with(MemberId::with($this->recipientId));
        $this->helper->assertBalanceIs($amount, $recipient);
    }
}
