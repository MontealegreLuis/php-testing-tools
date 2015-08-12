<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Bridges\Tests;

use Money\Money;
use PHPUnit_Framework_Constraint as Constraint;

class MoneyConstraint extends Constraint
{
    /** @var integer */
    private $amountExpected;

    /**
     * @param integer $expected
     */
    public function __construct($expected)
    {
        $this->amountExpected = (integer) $expected;
        parent::__construct();
    }

    /**
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
        return 'has the correct amount';
    }
}
