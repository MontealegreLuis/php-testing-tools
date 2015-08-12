<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Bridges\Tests;

use Money\Money;

trait ProvidesMoneyMatcher
{
    public function getMatchers()
    {
        return [
            'amount' => function(Money $subject, $value) {
                return $subject->getAmount() === $value;
            }
        ];
    }
}
