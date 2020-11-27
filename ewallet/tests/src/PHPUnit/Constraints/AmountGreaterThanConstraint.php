<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PHPUnit\Constraints;

use Money\Money;
use PHPUnit\Framework\Constraint\Constraint;

final class AmountGreaterThanConstraint extends Constraint
{
    private Money $lowerLimit;

    public function __construct(Money $lowerLimit)
    {
        $this->lowerLimit = $lowerLimit;
    }

    /**
     * Returns true if the provided amount is greater, than the current lower
     * limit
     *
     */
    protected function matches($other): bool
    {
        /** @var Money $other */
        return $this->lowerLimit->lessThan($other);
    }

    public function toString(): string
    {
        return "amount is not greater than {$this->lowerLimit->getAmount()}";
    }
}
