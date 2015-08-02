<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Money\Money;

trait MemberDictionary
{
    /**
     * @Transform :amount
     */
    public function transformStringToMoney($amount)
    {
        return Money::MXN((integer) $amount * 100);
    }
}
