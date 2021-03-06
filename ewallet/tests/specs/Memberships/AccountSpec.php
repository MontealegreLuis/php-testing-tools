<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace specs\Ewallet\Memberships;

use Ewallet\Memberships\InsufficientFunds;
use Money\Money;
use PhpSpec\ObjectBehavior;

class AccountSpec extends ObjectBehavior
{
    function it_has_an_initial_balance()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->balance()->shouldAmount(3000);
    }

    function it_increases_its_balance_after_a_deposit()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->deposit(Money::MXN(500));

        $this->balance()->shouldAmount(3500);
    }

    function it_decreases_its_balance_after_a_withdrawal()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->withdraw(Money::MXN(500));

        $this->balance()->shouldAmount(2500);
    }

    function it_cannot_be_overdrawn()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this
            ->shouldThrow(InsufficientFunds::class)
            ->duringWithdraw(Money::MXN(3500))
        ;
    }
}
