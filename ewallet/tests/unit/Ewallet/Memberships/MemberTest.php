<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use DataBuilders\A;
use Eris\Generator;
use Eris\TestTrait;
use Money\Money;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit\Framework\TestCase;

final class MemberTest extends TestCase
{
    use ProvidesMoneyConstraints;
    use TestTrait;

    /** @test */
    function sender_balance_should_decrease_after_funds_have_been_transferred()
    {
        $this
            ->forAll(Generator\choose(1, 10000))
            ->then(function (int $amount) {
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
            ->then(function (int $amount) {
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
