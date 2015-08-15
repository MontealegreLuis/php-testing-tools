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
    public function renderMember(MemberInformation $member)
    {
        return "{$member->name()} {$this->renderMoney($member->accountBalance())}";
    }

    /**
     * @param Money $money
     * @return string
     */
    public function renderMoney(Money $money)
    {
        return "\${$this->renderMoneyAmount(round($money->getAmount() / 100, 2))} {$money->getCurrency()}";
    }

    /**
     * @param integer $amount
     * @return string
     */
    public function renderMoneyAmount($amount)
    {
        return number_format($amount, 2);
    }
}
