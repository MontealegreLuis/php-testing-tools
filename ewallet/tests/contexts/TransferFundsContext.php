<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Application\DomainEvents\EventPublisher;
use Behat\Behat\Context\Context;
use DataBuilders\A;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;
use Ewallet\Memberships\InMemoryMembers;
use Ewallet\Memberships\MemberId;
use Money\Money;

/**
 * Defines steps for the 'Transfer funds' feature
 */
class TransferFundsContext implements Context
{
    use MemberDictionary;

    /** @var string */
    private $senderId = 'abc';

    /** @var string */
    private $recipientId = 'xyz';

    /** @var \Ewallet\Memberships\Members */
    private $members;

    /** @var TransferFundsResponderHelper */
    private $helper;

    /** @var TransferFundsAction */
    private $action;

    /** @BeforeScenario */
    public function prepare()
    {
        $this->members = new InMemoryMembers();
        $this->helper = new TransferFundsResponderHelper();
        $this->action = new TransferFundsAction($this->members, new EventPublisher());
        $this->action->attach($this->helper);
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
        $this->action->transfer(TransferFundsInput::from([
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId,
            'amount' => $amount->getAmount() / 100,
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
        $sender = $this->members->with(MemberId::withIdentity($this->senderId));
        $this->helper->assertBalanceIs($amount, $sender);
    }

    /**
     * @Then the recipient's balance should be :amount MXN
     */
    public function theRecipientsBalanceShouldBeMxn(Money $amount)
    {
        $recipient = $this->members->with(MemberId::withIdentity($this->recipientId));
        $this->helper->assertBalanceIs($amount, $recipient);
    }

    /**
     * @Then /^the sender is notified that she does not have enough funds to complete the transfer$/
     */
    public function theSenderIsNotifiedThatSheDoesNotHaveEnoughFundsToCompleteTheTransfer()
    {
        $this->helper->assertSenderDoesNotHaveEnoughFunds();
    }
}
