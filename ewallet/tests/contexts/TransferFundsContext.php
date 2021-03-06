<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Application\DomainEvents\EventPublisher;
use Behat\Behat\Context\Context;
use Behat\Ewallet\ManageWallet\TransferFunds\MemberDictionary;
use Behat\Ewallet\ManageWallet\TransferFunds\TransferFundsResponderHelper;
use DataBuilders\A;
use DataBuilders\Input;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsSummary;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Fakes\Ewallet\Memberships\InMemoryMembers;
use Money\Money;
use PHPUnit\Framework\Assert;

/**
 * Defines steps for the 'Transfer funds' feature
 */
final class TransferFundsContext implements Context
{
    use MemberDictionary;

    private string $senderId = 'abc';

    private string $recipientId = 'xyz';

    private Members $members;

    private TransferFundsResponderHelper $helper;

    private TransferFundsAction $action;

    private TransferFundsSummary $summary;

    /** @BeforeScenario */
    public function let()
    {
        $this->members = new InMemoryMembers();
        $this->helper = new TransferFundsResponderHelper();
        $this->action = new TransferFundsAction($this->members, new EventPublisher());
    }

    /**
     * @Given a sender with an account balance of :amount MXN
     */
    public function aSenderWithAnAccountBalanceOfMxn(Money $amount)
    {
        $sender = A::member()->withId($this->senderId)->withBalance($amount)->build();

        $this->members->save($sender);
    }

    /**
     * @Given a recipient with an account balance of :amount MXN
     */
    public function aRecipientWithAnAccountBalanceOfMxn(Money $amount)
    {
        $recipient = A::member()->withId($this->recipientId)->withBalance($amount)->build();

        $this->members->save($recipient);
    }

    /**
     * @When the sender transfers :amount MXN to the recipient
     */
    public function theSenderTransfersMxnToTheRecipient(Money $amount)
    {
        $this->summary = $this->action->transfer(Input::transferFunds([
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
        Assert::assertNotNull($this->summary, 'Transfer is incomplete.');
    }

    /**
     * @Then the sender's balance should be :amount MXN
     */
    public function theSendersBalanceShouldBeMxn(Money $amount)
    {
        $sender = $this->members->with(new MemberId($this->senderId));
        $this->helper->assertBalanceIs($amount, $sender);
    }

    /**
     * @Then the recipient's balance should be :amount MXN
     */
    public function theRecipientsBalanceShouldBeMxn(Money $amount)
    {
        $recipient = $this->members->with(new MemberId($this->recipientId));
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
