<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace spec\Ewallet\Accounts;

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
}
