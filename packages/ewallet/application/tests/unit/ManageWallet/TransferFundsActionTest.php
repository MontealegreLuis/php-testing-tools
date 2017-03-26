<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\DataBuilders\A;
use Ewallet\Memberships\{MemberId, InMemoryMembers};
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_allows_to_enter_transfer_information()
    {
        $senderId = MemberId::withIdentity('any');
        $this->action->enterTransferInformation($senderId);

        $this
            ->responder
            ->respondToEnterTransferInformation($senderId)
            ->shouldHaveBeenCalled()
        ;
    }

    /** @test */
    function it_allows_to_transfer_funds()
    {
        $input = $this->prophesize(TransferFundsInput::class);
        $input->isValid()->willReturn(true);
        $input->values()->willReturn([
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId,
            'amount' => $this->amountInCents
        ]);

        $this->action->transfer($input->reveal());

        $this
            ->responder
            ->respondToTransferCompleted(Argument::type(TransferFundsSummary::class))
            ->shouldHaveBeenCalled()
        ;
    }

    /** @test */
    function it_notifies_when_transfer_funds_information_is_invalid()
    {
        $values = [
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId,
        ];
        $errorMessages = ['amount' => 'No amount was provided'];

        $input = $this->prophesize(TransferFundsInput::class);
        $input->isValid()->willReturn(false);
        $input->values()->willReturn($values);
        $input->errorMessages()->willReturn($errorMessages);

        $this->action->transfer($input->reveal());

        $this
            ->responder
            ->respondToInvalidTransferInput($errorMessages, $values)
            ->shouldHaveBeenCalled()
        ;
    }

    /** @before */
    public function configureAction(): void
    {
        $this->responder = $this->prophesize(TransferFundsResponder::class);

        $members = new InMemoryMembers();
        $members->add(
            A::member()->withId($this->senderId)->withBalance(20000)->build()
        );
        $members->add(A::member()->withId($this->recipientId)->build());

        $this->action = new TransferFundsAction(
            $this->responder->reveal(),
            new TransferFunds($members)
        );
    }

    /** @var TransferFundsResponder */
    private $responder;

    /** @var TransferFundsAction */
    private $action;

    private $senderId = 'abc';
    private $recipientId = 'xyz';
    private $amountInCents = 100;
}
