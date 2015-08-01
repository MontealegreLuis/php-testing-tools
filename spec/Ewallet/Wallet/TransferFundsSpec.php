<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Wallet;

use Ewallet\Accounts\Members;
use EwalletTestsBridge\MembersBuilder;
use EwalletTestsBridge\ProvidesMoneyMatcher;
use Money\Money;
use PhpSpec\ObjectBehavior;

class TransferFundsSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    function it_should_transfer_funds_between_accounts(Members $members)
    {
        $member = MembersBuilder::aMember();
        $member
            ->withBalance(2000)
            ->build()
        ;
        $fromMember = $member->build();

        $member = MembersBuilder::aMember();
        $member
            ->withBalance(1000)
            ->build()
        ;
        $toMember = $member->build();

        $members->with($fromMember->id())->willReturn($fromMember);
        $members->with($toMember->id())->willReturn($toMember);
        $members->update($fromMember)->shouldBeCalled();
        $members->update($toMember)->shouldBeCalled();

        $this->beConstructedWith($members);

        $result = $this->transfer(
            $fromMember->id(), $toMember->id(), Money::MXN(500)
        );

        $result->fromMember()->accountBalance()->shouldAmount(1500);
        $result->toMember()->accountBalance()->shouldAmount(1500);
    }
}
