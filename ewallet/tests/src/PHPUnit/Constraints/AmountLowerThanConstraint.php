<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PHPUnit\Constraints;

use Money\Money;
use PHPUnit\Framework\Constraint\Constraint;

final class AmountLowerThanConstraint extends Constraint
{
    private Money $upperLimit;

    public function __construct(Money $upperLimit)
    {
        $this->upperLimit = $upperLimit;
    }

    /**
     * Returns true if the provided amount is lower, than the current upper
     * limit
     *
     */
    protected function matches($other): bool
    {
        /** @var Money $other */
        return $this->upperLimit->greaterThan($other);
    }

    public function toString(): string
    {
        return "amount is not lower than {$this->upperLimit->getAmount()}";
    }
}
