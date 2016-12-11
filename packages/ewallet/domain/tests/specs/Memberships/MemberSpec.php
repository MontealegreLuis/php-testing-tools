<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Memberships;

use Assert\InvalidArgumentException;
use Ewallet\Memberships\{Email, MemberId, InvalidTransfer};
use Ewallet\DataBuilders\A;
use Ewallet\PhpSpec\Matchers\ProvidesMoneyMatcher;
use Money\Money;
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
            MemberId::withIdentity(self::A_VALID_ID),
            self::A_VALID_NAME,
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT)
        ]);
    }

    function it_can_be_registered_with_an_initial_account_balance()
    {
        $this->information()->accountBalance()->shouldAmount(self::A_VALID_AMOUNT);
    }

    function it_transfers_funds_to_a_recipient()
    {
        $this->transfer(Money::MXN(500), A::member()->build());

        $this->information()->accountBalance()->shouldAmount(1500);
    }

    function it_receives_funds_from_a_sender()
    {
        $sender = A::member()->withBalance(1000)->build();

        $sender->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->information()->accountBalance()->shouldAmount(2500);
    }

    function it_cannot_be_registered_with_an_empty_name()
    {
        $this->beConstructedThrough('withAccountBalance', [
            MemberId::withIdentity(self::A_VALID_ID),
            '',
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT)
        ]);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_cannot_transfer_a_negative_amount()
    {
        $this
            ->shouldThrow(InvalidTransfer::class)
            ->duringTransfer(Money::MXN(-5000), A::member()->build())
        ;
    }

    function it_cannot_transfer_zero()
    {
        $this
            ->shouldThrow(InvalidTransfer::class)
            ->duringTransfer(Money::MXN(0), A::member()->build())
        ;
    }

    function it_records_that_a_transfer_was_made()
    {
        $this->transfer(Money::MXN(500), A::member()->build());

        $this->events()->count()->shouldBe(1);
    }

    function it_can_recognize_her_identity()
    {
        $sameMember = A::member()->withId(self::A_VALID_ID)->build();

        $this->equals($sameMember)->shouldBe(true);
    }

    function it_recognizes_other_members_identities()
    {
        $aDifferentMember = A::member()->withId('xyz')->build();

        $this->equals($aDifferentMember)->shouldBe(false);
    }
}
