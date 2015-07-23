<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Accounts;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use EwalletTestsBridge\MembersBuilder;
use Money\Money;
use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough(
            'withAccountBalance',
            [Identifier::fromString('abc'), Money::MXN(2000)]
        );
    }

    function it_should_be_created_with_a_given_account_balance()
    {
        $this->accountBalance()->getAmount()->shouldBe(2000);
    }

    function it_should_transfer_funds_to_another_member()
    {
        $toMember = MembersBuilder::aMember()->build();

        $this->transfer(Money::MXN(500), $toMember);

        $this->accountBalance()->getAmount()->shouldBe(1500);
    }

    function it_should_receive_funds_from_another_member()
    {
        $fromMember = MembersBuilder::aMember()->withBalance(1000)->build();

        $fromMember->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->accountBalance()->getAmount()->shouldBe(2500);
    }

    function it_should_be_recognizable_by_its_id()
    {
        $this->id()->equals(Identifier::fromString('abc'))->shouldBe(true);
    }
}
