<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\ManageWallet;

use Ewallet\Memberships\Members;
use Ewallet\DataBuilders\A;
use Ewallet\PhpSpec\Matchers\ProvidesMoneyMatcher;
use Ewallet\ManageWallet\{CanTransferFunds, TransferFundsInformation, TransferFundsSummary};
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransferFundsSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    function it_transfers_funds_between_members(
        Members $members,
        CanTransferFunds $action
    ) {
        $sender = A::member()->withBalance(2000)->build();
        $recipient = A::member()->withBalance(1000)->build();

        $members->with($sender->information()->id())->willReturn($sender);
        $members->with($recipient->information()->id())->willReturn($recipient);
        $members->update($sender)->shouldBeCalled();
        $members->update($recipient)->shouldBeCalled();

        $this->beConstructedWith($members);
        $this->attach($action);

        $this->transfer(TransferFundsInformation::from([
            'senderId' => $sender->information()->id()->value(),
            'recipientId' => $recipient->information()->id()->value(),
            'amount' => 5,
        ]));

        $action
            ->transferCompleted(Argument::type(TransferFundsSummary::class))
            ->shouldHaveBeenCalled()
        ;
    }
}
