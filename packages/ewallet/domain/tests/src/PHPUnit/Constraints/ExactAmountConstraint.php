<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PHPUnit\Constraints;

use Money\Money;
use PHPUnit_Framework_Constraint as Constraint;

class ExactAmountConstraint extends Constraint
{
    /** @var integer */
    private $amountExpected;

    /**
     * @param int $expected
     */
    public function __construct(int $expected)
    {
        $this->amountExpected = $expected;
        parent::__construct();
    }

    /**
     * Returns true only if the amount of provided `Money` object is equal to
     * the expected one
     *
     * @param mixed $other
     * @return boolean
     */
    protected function matches($other)
    {
        /** @var Money $other */
        return $this->amountExpected === $other->getAmount();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "has the correct amount, expecting {$this->amountExpected}";
    }
}
