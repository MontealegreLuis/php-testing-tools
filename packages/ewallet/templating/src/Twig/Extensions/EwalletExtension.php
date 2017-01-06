<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Twig\Extensions;

use Ewallet\Memberships\{MemberInformation, MemberFormatter};
use Money\Money;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

class EwalletExtension extends Extension
{
    /** @var MemberFormatter */
    private $formatter;

    public function __construct(MemberFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @return SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('member', [$this, 'formatMember']),
            new SimpleFunction('money_amount', [$this, 'formatMoneyAmount']),
            new SimpleFunction('money', [$this, 'formatMoney']),
        ];
    }

    public function formatMember(MemberInformation $member): string
    {
        return $this->formatter->formatMember($member);
    }

    public function formatMoney(Money $money): string
    {
        return $this->formatter->formatMoney($money);
    }

    public function formatMoneyAmount(int $amount): string
    {
        return $this->formatter->formatMoneyAmount($amount);
    }

    /**
     * @return string The extension name
     */
    public function getName()
    {
        return 'ewallet';
    }
}
