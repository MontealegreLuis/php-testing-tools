<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\View;

use Ewallet\Accounts\MemberInformation;
use Money\Money;

class MemberFormatter
{
    /**
     * @param MemberInformation $member
     * @return string
     */
    public function formatMember(MemberInformation $member)
    {
        return "{$member->name()} {$this->formatMoney($member->accountBalance())}";
    }

    /**
     * @param Money $money
     * @return string
     */
    public function formatMoney(Money $money)
    {
        return "\${$this->formatMoneyAmount(round($money->getAmount() / 100, 2))} {$money->getCurrency()}";
    }

    /**
     * @param float $amount
     * @return string
     */
    public function formatMoneyAmount($amount)
    {
        return number_format($amount, 2);
    }
}
