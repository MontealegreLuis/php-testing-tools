<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Accounts;

use Ewallet\Accounts\InsufficientFunds;
use Ewallet\Bridges\Tests\ProvidesMoneyMatcher;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccountSpec extends ObjectBehavior
{
    use ProvidesMoneyMatcher;

    function it_should_be_created_with_a_specific_balance()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->information()->balance()->shouldAmount(3000);
    }

    function it_should_increase_balance_after_a_deposit()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->deposit(Money::MXN(500));

        $this->information()->balance()->shouldAmount(3500);
    }

    function it_should_decrease_balance_after_a_withdrawal()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this->withdraw(Money::MXN(500));

        $this->information()->balance()->shouldAmount(2500);
    }

    function it_should_not_allow_withdrawing_more_than_the_current_balance()
    {
        $this->beConstructedThrough('withBalance', [Money::MXN(3000)]);

        $this
            ->shouldThrow(InsufficientFunds::class)
            ->duringWithdraw(Money::MXN(3500))
        ;
    }
}
