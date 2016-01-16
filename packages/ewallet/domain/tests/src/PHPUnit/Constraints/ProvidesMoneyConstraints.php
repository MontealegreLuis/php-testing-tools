<?php
/**
 * PHP version 5.6
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
     * @param integer $amountExpected
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceAmounts(
        $amountExpected, Member $forMember, $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountCorrect((integer) $amountExpected),
            $message
        );
    }

    /**
     * @param integer $amountExpected
     * @return ExactAmountConstraint
     */
    public static function isBalanceAmountCorrect($amountExpected)
    {
        return new ExactAmountConstraint($amountExpected);
    }

    /**
     * @param integer $upperLimitAmount
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceIsLowerThan(
        $upperLimitAmount, Member $forMember, $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountLowerThan($upperLimitAmount),
            $message
        );
    }

    /**
     * @param integer $upperLimit
     * @return AmountLowerThanConstraint
     */
    public static function isBalanceAmountLowerThan($upperLimit)
    {
        return new AmountLowerThanConstraint(Money::MXN((integer) $upperLimit));
    }

    /**
     * @param integer $lowerLimitAmount
     * @param Member $forMember
     * @param string $message
     */
    public static function assertBalanceIsGreaterThan(
        $lowerLimitAmount, Member $forMember, $message = ''
    ) {
        TestCase::assertThat(
            $forMember->information()->accountBalance(),
            self::isBalanceAmountGreaterThan($lowerLimitAmount),
            $message
        );
    }

    /**
     * @param integer $lowerLimit
     * @return AmountLowerThanConstraint
     */
    public static function isBalanceAmountGreaterThan($lowerLimit)
    {
        return new AmountGreaterThanConstraint(Money::MXN((integer) $lowerLimit));
    }
}
