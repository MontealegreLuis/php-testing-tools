<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

trait MemberDictionary
{
    /**
     * @Transform :amount
     */
    public function transformStringToMoney($amount)
    {
        return (integer) $amount * 100;
    }
}
