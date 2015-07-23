<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Accounts;

use Ewallet\Accounts\Member;
use Money\Money;
use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    function it_should_be_created_with_a_given_account_balance()
    {
        $amount = Money::MXN(2000);
        $this->beConstructedThrough('withAccountBalance', [$amount]);
        $this->balance()->getAmount()->shouldBe(2000);
    }

    function it_should_transfer_funds_to_another_member()
    {
        $this->beConstructedThrough('withAccountBalance', [Money::MXN(2000)]);
        $toMember = Member::withAccountBalance(Money::MXN(3000));

        $this->transfer(Money::MXN(500), $toMember);

        $this->balance()->getAmount()->shouldBe(1500);
    }

    function it_should_receive_funds_from_another_member()
    {
        $this->beConstructedThrough('withAccountBalance', [Money::MXN(2000)]);
        $fromMember = Member::withAccountBalance(Money::MXN(3000));

        $fromMember->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->balance()->getAmount()->shouldBe(2500);
    }
}
