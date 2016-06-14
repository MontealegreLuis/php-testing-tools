<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PHPUnit\Constraints;

use Ewallet\Accounts\Member;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

trait ProvidesMoneyConstraints
{
    /**
     * @param int $amountExpected
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceAmounts(
        int $amountExpected,
        Member $forMember,
        string $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountCorrect($amountExpected),
            $message
        );
    }

    /**
     * @param int $amountExpected
     * @return ExactAmountConstraint
     */
    public static function isBalanceAmountCorrect(
        int $amountExpected
    ): ExactAmountConstraint
    {
        return new ExactAmountConstraint($amountExpected);
    }

    /**
     * @param int $upperLimitAmount
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceIsLowerThan(
        int $upperLimitAmount,
        Member $forMember,
        string $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountLowerThan($upperLimitAmount),
            $message
        );
    }

    /**
     * @param int $upperLimit
     * @return AmountLowerThanConstraint
     */
    public static function isBalanceAmountLowerThan(
        int $upperLimit
    ): AmountLowerThanConstraint
    {
        return new AmountLowerThanConstraint(Money::MXN($upperLimit));
    }

    /**
     * @param int $lowerLimitAmount
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceIsGreaterThan(
        int $lowerLimitAmount,
        Member $forMember,
        string $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountGreaterThan($lowerLimitAmount),
            $message
        );
    }

    /**
     * @param int $lowerLimit
     * @return AmountGreaterThanConstraint
     */
    public static function isBalanceAmountGreaterThan(
        int $lowerLimit
    ): AmountGreaterThanConstraint
    {
        return new AmountGreaterThanConstraint(Money::MXN($lowerLimit));
    }
}
