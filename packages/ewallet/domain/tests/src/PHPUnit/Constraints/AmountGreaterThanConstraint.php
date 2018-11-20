<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PHPUnit\Constraints;

use Money\Money;
use PHPUnit\Framework\Constraint\Constraint;

class AmountGreaterThanConstraint extends Constraint
{
    /** @var Money */
    private $lowerLimit;

    public function __construct(Money $lowerLimit)
    {
        $this->lowerLimit = $lowerLimit;
        parent::__construct();
    }

    /**
     * Returns true if the provided amount is greater, than the current lower
     * limit
     *
     * @param  Money $other
     */
    protected function matches($other): bool
    {
        return $this->lowerLimit->lessThan($other);
    }

    public function toString(): string
    {
        return "amount is not greater than {$this->lowerLimit->getAmount()}";
    }
}
