<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhpSpec\Matchers;

use Money\Money;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\BasicMatcher;

/**
 * Adds `shouldAmount` matcher to verify that an amount expressed in
 * cents is equal to the amount of the subject `Money` object
 */
final class MoneyMatcher extends BasicMatcher
{
    /**
     * Verifies that a Money object has the correct amount
     *
     * @example $this->account->balance()->shouldAmount($amount)
     * @param mixed $subject
     * @param mixed[] $arguments
     */
    public function supports(string $name, $subject, array $arguments): bool
    {
        return $name === 'amount' && \count($arguments) === 1;
    }

    /**
     * @param mixed $subject
     * @param mixed[] $arguments
     */
    protected function matches($subject, array $arguments): bool
    {
        $expectedAmount = $arguments[0];
        /** @var Money $subject */
        return $subject->getAmount() === (string) $expectedAmount;
    }

    /**
     * @param mixed $subject
     * @param mixed[] $arguments
     */
    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expectedAmount = $arguments[0];
        /** @var Money $subject */
        return new FailureException(sprintf(
            'Current money amount should be "%s" not "%s".',
            $expectedAmount,
            $subject->getAmount()
        ));
    }

    /**
     * @param mixed $subject
     * @param mixed[] $arguments
     */
    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expectedAmount = $arguments[0];
        /** @var Money $subject */
        return new FailureException(sprintf(
            'Current money amount "%s" should not be "%s".',
            $expectedAmount,
            $subject->getAmount()
        ));
    }
}
