<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PHPUnit\Constraints;

use Ewallet\Memberships\Member;
use Money\Money;
use PHPUnit\Framework\TestCase;

trait ProvidesMoneyConstraints
{
    public static function assertBalanceAmounts(int $amountExpected, Member $forMember, string $message = ''): void
    {
        TestCase::assertThat($forMember->accountBalance(), self::isBalanceAmountCorrect($amountExpected), $message);
    }

    public static function isBalanceAmountCorrect(int $amountExpected): ExactAmountConstraint
    {
        return new ExactAmountConstraint($amountExpected);
    }

    public static function assertBalanceIsLowerThan(int $upperLimitAmount, Member $forMember, string $message = ''): void
    {
        TestCase::assertThat($forMember->accountBalance(), self::isBalanceAmountLowerThan($upperLimitAmount), $message);
    }

    public static function isBalanceAmountLowerThan(int $upperLimit): AmountLowerThanConstraint
    {
        return new AmountLowerThanConstraint(Money::MXN($upperLimit));
    }

    public static function assertBalanceIsGreaterThan(int $lowerLimitAmount, Member $forMember, string $message = ''): void
    {
        TestCase::assertThat($forMember->accountBalance(), self::isBalanceAmountGreaterThan($lowerLimitAmount), $message);
    }

    public static function isBalanceAmountGreaterThan(int $lowerLimit): AmountGreaterThanConstraint
    {
        return new AmountGreaterThanConstraint(Money::MXN($lowerLimit));
    }
}
