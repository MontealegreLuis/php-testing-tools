<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Accounts;

use Assert\InvalidArgumentException;
use DataBuilders\A;
use Ewallet\Accounts\Email;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\InvalidTransferAmount;
use Money\Money;
use PhpSpec\Matchers\ProvidesMoneyMatcher;
use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    const A_VALID_ID = 'abc';
    const A_VALID_NAME = 'Luis Montealegre';
    const A_VALID_EMAIL = 'montealegreluis@gmail.com';
    const A_VALID_AMOUNT = 2000;

    function let()
    {
        $this->beConstructedThrough( 'withAccountBalance', [
            Identifier::with(self::A_VALID_ID),
            self::A_VALID_NAME,
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT)
        ]);
    }

    function it_should_be_created_with_a_given_account_balance()
    {
        $this->information()->accountBalance()->shouldAmount(self::A_VALID_AMOUNT);
    }

    function it_should_transfer_funds_to_another_member()
    {
        $this->transfer(Money::MXN(500), A::member()->build());

        $this->information()->accountBalance()->shouldAmount(1500);
    }

    function it_should_receive_funds_from_another_member()
    {
        $fromMember = A::member()->withBalance(1000)->build();

        $fromMember->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->information()->accountBalance()->shouldAmount(2500);
    }

    function it_should_not_allow_an_empty_name()
    {
        $this->beConstructedThrough('withAccountBalance', [
            Identifier::with(self::A_VALID_ID),
            '',
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT)
        ]);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_should_not_allow_to_transfer_a_negative_amount()
    {
        $this
            ->shouldThrow(InvalidTransferAmount::class)
            ->duringTransfer(Money::MXN(-5000), A::member()->build())
        ;
    }

    function it_should_record_that_a_transfer_was_made()
    {
        $this->transfer(Money::MXN(500), A::member()->build());

        $this->events()->count()->shouldBe(1);
    }

    function it_should_know_when_another_member_is_equal_to_it()
    {
        $sameMember = A::member()->withId(self::A_VALID_ID)->build();

        $this->equals($sameMember)->shouldBe(true);
    }

    function it_should_know_when_another_member_is_not_equal_to_it()
    {
        $aDifferentMember = A::member()->withId('xyz')->build();

        $this->equals($aDifferentMember)->shouldBe(false);
    }
}
