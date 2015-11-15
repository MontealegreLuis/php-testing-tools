<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Twig\Extensions;

use Ewallet\Accounts\MemberInformation;
use EwalletModule\View\MemberFormatter;
use Money\Money;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

class EwalletExtension extends Extension
{
    /** @var MemberFormatter */
    private $formatter;

    /**
     * @param MemberFormatter $formatter
     */
    public function __construct(MemberFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('member', [$this, 'formatMember']),
            new SimpleFunction('money_amount', [$this, 'formatMoneyAmount']),
            new SimpleFunction('money', [$this, 'formatMoney']),
        ];
    }

    /**
     * @param MemberInformation $member
     * @return string
     */
    public function formatMember(MemberInformation $member)
    {
        return $this->formatter->formatMember($member);
    }

    /**
     * @param Money $money
     * @return string
     */
    public function formatMoney(Money $money)
    {
        return $this->formatter->formatMoney($money);
    }

    /**
     * @param integer $amount
     * @return string
     */
    public function formatMoneyAmount($amount)
    {
        return $this->formatter->formatMoneyAmount($amount);
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
