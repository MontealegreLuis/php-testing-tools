<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletTwigBridge\Extensions;

use Ewallet\Accounts\MemberInformation;
use Money\Money;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

class EwalletExtension extends Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('member', [$this, 'renderMember']),
            new SimpleFunction('money', [$this, 'renderMoney']),
        ];
    }

    /**
     * @param MemberInformation $member
     * @return string
     */
    public function renderMember(MemberInformation $member)
    {
        return $member->name() . ' ' . $this->renderMoney($member->accountBalance());
    }

    /**
     * @param Money $money
     * @return string
     */
    public function renderMoney(Money $money)
    {
        return '$' . round($money->getAmount() / 100, 2) . ' ' . $money->getCurrency();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ewallet';
    }
}
