<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use DataBuilders\A;
use Eris\Generator;
use Eris\TestTrait;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class MemberTest extends TestCase
{
    use TestTrait, ProvidesMoneyConstraints;

    /** @test */
    function giver_balance_should_decrease_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\choose(1, 10000))
            ->then(function($amount) {
                $fromMember = A::member()->withBalance(10000)->build();
                $toMember = A::member()->build();

                $fromMember->transfer(Money::MXN($amount), $toMember);

                $this->assertBalanceIsLowerThan(
                    10000,
                    $fromMember,
                    "Transferring {$amount} increased balance of sender member"
                );
            });
        ;
    }

    /** @test */
    function beneficiary_balance_should_increase_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\choose(1, 10000))
            ->then(function($amount) {
                $fromMember = A::member()->withBalance(10000)->build();
                $toMember = A::member()->withBalance(5000)->build();

                $fromMember->transfer(Money::MXN($amount), $toMember);

                $this->assertBalanceIsGreaterThan(
                    5000,
                    $toMember,
                    "Transferring {$amount} increased balance of receiver member"
                );
            });
        ;
    }
}
