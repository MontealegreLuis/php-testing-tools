<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PHPUnit\Constraints;

use Money\Money;
use PHPUnit\Framework\Constraint\Constraint;

final class ExactAmountConstraint extends Constraint
{
    private int $expectedAmount;

    public function __construct(int $expected)
    {
        $this->expectedAmount = $expected;
    }

    /**
     * Returns true only if the amount of provided `Money` object is equal to
     * the expected one
     */
    protected function matches($other): bool
    {
        /** @var Money $other */
        return $this->expectedAmount === (int) $other->getAmount();
    }

    public function toString(): string
    {
        return "has the correct amount, expecting {$this->expectedAmount}";
    }
}
