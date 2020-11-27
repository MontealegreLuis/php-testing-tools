<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\DomainEvents\EventPublisher;
use DataBuilders\A;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use Fakes\Ewallet\Memberships\InMemoryMembers;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class TransferFundsActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_provides_feedback_if_sender_cannot_be_found()
    {
        $input = new TransferFundsInput([
            'senderId' => 'unknown sender',
            'recipientId' => 'unknown recipient',
            'amount' => 10, // 10 MXN
        ]);

        $this->expectException(UnknownMember::class);
        $this->action->transfer($input);
    }

    /** @test */
    function it_provides_feedback_if_recipient_cannot_be_found()
    {
        $sender = A::member()->build();
        $this->members->save($sender);
        $input = new TransferFundsInput([
            'senderId' => $sender->idValue(),
            'recipientId' => 'unknown recipient',
            'amount' => 10, // 10 MXN
        ]);

        $this->expectException(UnknownMember::class);
        $this->action->transfer($input);
    }

    /** @test */
    function it_fails_transfer_if_recipient_does_not_have_enough_funds()
    {
        $sender = A::member()->withBalance(500)->build(); // 5 MXN
        $recipient = A::member()->build();
        $this->members->save($sender);
        $this->members->save($recipient);
        $input = new TransferFundsInput([
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
        $this->members->save($sender);
        $this->members->save($recipient);
        $input = new TransferFundsInput([
            'senderId' => $sender->idValue(),
            'recipientId' => $recipient->idValue(),
            'amount' => 10, // 10 MXN
        ]);

        $summary = $this->action->transfer($input);

        $this->assertEquals($recipient->id(), $summary->recipientId());
        $this->assertEquals($sender->id(), $summary->senderId());
    }

    /** @before */
    function let()
    {
        $this->members = new InMemoryMembers();
        $this->action = new TransferFundsAction($this->members, new EventPublisher());
    }

    private Members $members;

    private TransferFundsAction $action;
}
