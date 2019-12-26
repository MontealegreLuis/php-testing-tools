<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\DomainEvents\EventPublisher;
use DataBuilders\A;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\UnknownMember;
use Fakes\Ewallet\Memberships\InMemoryMembers;
use LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_fails_to_transfer_if_no_responder_is_given()
    {
        $this->expectException(LogicException::class);
        $this->action->transfer(TransferFundsInput::from([]));
    }

    /** @test */
    function it_provides_feedback_if_input_is_invalid()
    {
        $this->action->attach($this->responder->reveal());
        $input = TransferFundsInput::from([]);

        $this->action->transfer($input);

        $this->responder->respondToInvalidInput($input)->shouldHaveBeenCalled();
        $this->assertFalse($input->isValid());
    }

    /** @test */
    function it_provides_feedback_if_sender_cannot_be_found()
    {
        $this->action->attach($this->responder->reveal());
        $input = TransferFundsInput::from([
            'senderId' => 'unknown sender',
            'recipientId' => 'unknown recipient',
            'amount' => 10, // 10 MXN
        ]);

        $this->action->transfer($input);

        $this->responder->respondToUnknownMember(Argument::type(UnknownMember::class))->shouldHaveBeenCalled();
        $this->assertTrue($input->isValid());
    }

    /** @test */
    function it_provides_feedback_if_recipient_cannot_be_found()
    {
        $sender = A::member()->build();
        $this->members->add($sender);
        $this->action->attach($this->responder->reveal());
        $input = TransferFundsInput::from([
            'senderId' => $sender->idValue(),
            'recipientId' => 'unknown recipient',
            'amount' => 10, // 10 MXN
        ]);

        $this->action->transfer($input);

        $this->responder->respondToUnknownMember(Argument::type(UnknownMember::class))->shouldHaveBeenCalled();
        $this->assertTrue($input->isValid());
    }

    /** @test */
    function it_fails_transfer_if_recipient_does_not_have_enough_funds()
    {
        $sender = A::member()->withBalance(500)->build(); // 5 MXN
        $recipient = A::member()->build();
        $this->members->add($sender);
        $this->members->add($recipient);
        $this->action->attach($this->responder->reveal());
        $input = TransferFundsInput::from([
            'senderId' => $sender->idValue(),
            'recipientId' => $recipient->idValue(),
            'amount' => 10, // 10 MXN
        ]);

        $this->expectException(InsufficientFunds::class);
        $this->action->transfer($input);
    }

    /** @test */
    function it_provides_feedback_if_transfer_is_successfully_completed()
    {
        $sender = A::member()->withBalance(50000)->build(); // 500 MXN
        $recipient = A::member()->build();
        $this->members->add($sender);
        $this->members->add($recipient);
        $this->action->attach($this->responder->reveal());
        $input = TransferFundsInput::from([
            'senderId' => $sender->idValue(),
            'recipientId' => $recipient->idValue(),
            'amount' => 10, // 10 MXN
        ]);

        $this->action->transfer($input);

        $this->responder
            ->respondToTransferCompleted(Argument::type(TransferFundsSummary::class))
            ->shouldHaveBeenCalled();
        $this->assertTrue($input->isValid());
    }

    /** @before */
    function configure()
    {
        $this->members = new InMemoryMembers();
        $this->responder = $this->prophesize(TransferFundsResponder::class);
        $this->action = new TransferFundsAction($this->members, new EventPublisher());
    }

    /** @var \Ewallet\Memberships\Members */
    private $members;

    /** @var TransferFundsResponder */
    private $responder;

    /** @var TransferFundsAction */
    private $action;
}
