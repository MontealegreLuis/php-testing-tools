<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Eris\{Generator, TestTrait};
use Ewallet\DataBuilders\A;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MemberTest extends TestCase
{
    use TestTrait, ProvidesMoneyConstraints;

    /** @test */
    function sender_balance_should_decrease_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\choose(1, 10000))
            ->then(function(int $amount) {
                $initialBalance = 10000;
                $sender = A::member()->withBalance($initialBalance)->build();
                $recipient = A::member()->build();

                $sender->transfer(Money::MXN($amount), $recipient);

                $this->assertBalanceIsLowerThan(
                    $initialBalance,
                    $sender,
                    "Transferring {$amount} increased balance of sender member"
                );
            })
        ;
    }

    /** @test */
    function recipient_balance_should_increase_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\choose(1, 10000))
            ->then(function(int $amount) {
                $initialBalance = 5000;
                $sender = A::member()->withBalance(10000)->build();
                $recipient = A::member()->withBalance($initialBalance)->build();

                $sender->transfer(Money::MXN($amount), $recipient);

                $this->assertBalanceIsGreaterThan(
                    $initialBalance,
                    $recipient,
                    "Transferring {$amount} increased balance of receiver member"
                );
            })
        ;
    }
}
