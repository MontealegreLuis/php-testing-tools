<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Eris\Generator;
use Eris\TestTrait;
use Ewallet\Bridges\Tests\A;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class MemberTest extends TestCase
{
    use TestTrait;

    /** @test */
    function giver_balance_should_decrease_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\int(1, 10000))
            ->then(function($amount) {
                $fromMember = A::member()->withBalance(10000)->build();
                $toMember = A::member()->build();
                $fromMember->transfer(Money::MXN($amount), $toMember);
                $currentBalance = $fromMember->information()->accountBalance()->getAmount();
                $this->assertLessThan(
                    10000,
                    $currentBalance,
                    "{$currentBalance} > 10000, {$amount} transferred"
                );
            });
        ;
    }

    /** @test */
    function beneficiary_balance_should_increase_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\int(1, 10000))
            ->then(function($amount) {
                $fromMember = A::member()->withBalance(10000)->build();
                $toMember = A::member()->withBalance(5000)->build();
                $fromMember->transfer(Money::MXN($amount), $toMember);
                $currentBalance = $toMember->information()->accountBalance()->getAmount();
                $this->assertGreaterThan(
                    5000,
                    $currentBalance,
                    "{$currentBalance} < 5000, {$amount} transferred"
                );
            });
        ;
    }
}
