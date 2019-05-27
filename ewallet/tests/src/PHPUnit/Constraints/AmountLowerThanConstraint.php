<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PHPUnit\Constraints;

use Money\Money;
use PHPUnit\Framework\Constraint\Constraint;

class AmountLowerThanConstraint extends Constraint
{
    /** @var Money */
    private $upperLimit;

    public function __construct(Money $upperLimit)
    {
        $this->upperLimit = $upperLimit;
        parent::__construct();
    }

    /**
     * Returns true if the provided amount is lower, than the current upper
     * limit
     *
     * @param  Money $other
     */
    protected function matches($other): bool
    {
        return $this->upperLimit->greaterThan($other);
    }

    public function toString(): string
    {
        return "amount is not lower than {$this->upperLimit->getAmount()}";
    }
}
