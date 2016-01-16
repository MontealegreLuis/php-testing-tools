<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Wallet;

use DataBuilders\A;
use Ewallet\Accounts\Members;
use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsInformation;
use Ewallet\Wallet\TransferFundsResult;
use PhpSpec\Matchers\ProvidesMoneyMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransferFundsSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    function it_should_transfer_funds_between_accounts(
        Members $members,
        TransferFundsNotifier $notifier
    ) {
        $fromMember = A::member()->withBalance(2000)->build();
        $toMember = A::member()->withBalance(1000)->build();

        $members->with($fromMember->information()->id())->willReturn($fromMember);
        $members->with($toMember->information()->id())->willReturn($toMember);
        $members->update($fromMember)->shouldBeCalled();
        $members->update($toMember)->shouldBeCalled();

        $this->beConstructedWith($members);
        $this->attach($notifier);

        $this->transfer(TransferFundsInformation::from([
            'fromMemberId' => (string) $fromMember->information()->id(),
            'toMemberId' => (string) $toMember->information()->id(),
            'amount' => 5,
        ]));

        $notifier
            ->transferCompleted(Argument::type(TransferFundsResult::class))
            ->shouldHaveBeenCalled()
        ;
    }
}
