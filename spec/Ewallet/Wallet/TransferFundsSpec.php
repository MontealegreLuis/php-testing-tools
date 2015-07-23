<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Wallet;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
use Money\Money;
use PhpSpec\ObjectBehavior;

class TransferFundsSpec extends ObjectBehavior
{
    function it_should_transfer_funds_between_accounts(Members $members)
    {
        $fromId = Identifier::fromString('abc');
        $toId = Identifier::fromString('xyz');
        $fromMember = Member::withAccountBalance($fromId, Money::MXN(2000));
        $toMember = Member::withAccountBalance($toId, Money::MXN(1000));

        $members->with($fromId)->willReturn($fromMember);
        $members->with($toId)->willReturn($toMember);
        $members->update($fromMember)->shouldBeCalled();
        $members->update($toMember)->shouldBeCalled();

        $this->beConstructedWith($members);

        $result = $this->transfer($fromId, $toId, Money::MXN(500));

        $result->fromMember()->accountBalance()->getAmount()->shouldBe(1500);
        $result->toMember()->accountBalance()->getAmount()->shouldBe(1500);
    }
}
