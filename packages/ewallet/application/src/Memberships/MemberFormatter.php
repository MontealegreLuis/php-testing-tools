<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Money\Money;

/**
 * Format money, and members information uniformly across all applications
 */
class MemberFormatter
{
    public function formatMember(MemberInformation $member): string
    {
        return "{$member->name()} {$this->formatMoney($member->accountBalance())}";
    }

    public function formatMoney(Money $money): string
    {
        return "\${$this->formatMoneyAmount($money->getAmount() / 100)} {$money->getCurrency()}";
    }

    public function formatMoneyAmount(float $amount): string
    {
        return number_format($amount, 2);
    }
}
