<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Accounts;

use Assert\InvalidArgumentException;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\InvalidTransferAmount;
use EwalletTestsBridge\MembersBuilder;
use EwalletTestsBridge\ProvidesMoneyMatcher;
use Money\Money;
use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    function let()
    {
        $this->beConstructedThrough(
            'withAccountBalance',
            [Identifier::fromString('abc'), 'Luis', Money::MXN(2000)]
        );
    }

    function it_should_be_created_with_a_given_account_balance()
    {
        $this->information()->accountBalance()->shouldAmount(2000);
    }

    function it_should_transfer_funds_to_another_member()
    {
        $toMember = MembersBuilder::aMember()->build();

        $this->transfer(Money::MXN(500), $toMember);

        $this->information()->accountBalance()->shouldAmount(1500);
    }

    function it_should_receive_funds_from_another_member()
    {
        $fromMember = MembersBuilder::aMember()->withBalance(1000)->build();

        $fromMember->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->information()->accountBalance()->shouldAmount(2500);
    }

    function it_should_be_recognizable_by_its_id()
    {
        $this->information()->id()->equals(Identifier::fromString('abc'))->shouldBe(true);
    }

    function it_should_not_allow_an_empty_name()
    {
        $this->beConstructedThrough(
            'withAccountBalance',
            [Identifier::fromString('abc'), '', Money::MXN(2000)]
        );
        try {
            $this->getWrappedObject();
            throw new ExampleException('Expected exception was not thrown');
        }
        catch(InvalidArgumentException $e) {}
    }

    function it_should_not_allow_to_transfer_a_negative_amount()
    {
        $toMember = MembersBuilder::aMember()->build();

        $this
            ->shouldThrow(InvalidTransferAmount::class)
            ->duringTransfer(Money::MXN(-5000), $toMember)
        ;
    }
}
