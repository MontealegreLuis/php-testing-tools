<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace specs\Ewallet\Memberships;

use DataBuilders\A;
use Ewallet\Memberships\Email;
use Ewallet\Memberships\InvalidTransfer;
use Ewallet\Memberships\MemberId;
use InvalidArgumentException;
use Money\Money;
use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    private const A_VALID_ID = 'abc';
    private const A_VALID_NAME = 'Luis Montealegre';
    private const A_VALID_EMAIL = 'montealegreluis@gmail.com';
    private const A_VALID_AMOUNT = 2000;

    function let()
    {
        $this->beConstructedThrough('withAccountBalance', [
            new MemberId(self::A_VALID_ID),
            self::A_VALID_NAME,
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT),
        ]);
    }

    function it_can_be_registered_with_an_initial_account_balance()
    {
        $this->accountBalance()->shouldAmount(self::A_VALID_AMOUNT);
    }

    function it_transfers_funds_to_a_recipient()
    {
        $this->transfer(Money::MXN(500), A::member()->build());

        $this->accountBalance()->shouldAmount(1500);
    }

    function it_receives_funds_from_a_sender()
    {
        $sender = A::member()->withBalance(1000)->build();

        $sender->transfer(Money::MXN(500), $this->getWrappedObject());

        $this->accountBalance()->shouldAmount(2500);
    }

    function it_cannot_be_registered_with_an_empty_name()
    {
        $this->beConstructedThrough('withAccountBalance', [
            new MemberId(self::A_VALID_ID),
            '',
            new Email(self::A_VALID_EMAIL),
            Money::MXN(self::A_VALID_AMOUNT),
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

        $this->events()->shouldHaveCount(1);
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
