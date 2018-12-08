<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Twig\Ewallet\Extensions;

use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberFormatter;
use Money\Money;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EwalletExtension extends AbstractExtension
{
    /** @var MemberFormatter */
    private $formatter;

    public function __construct(MemberFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('member', [$this, 'formatMember']),
            new TwigFunction('money_amount', [$this, 'formatMoneyAmount']),
            new TwigFunction('money', [$this, 'formatMoney']),
        ];
    }

    public function formatMember(Member $member): string
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

    /** @return string The extension name */
    public function getName(): string
    {
        return 'ewallet';
    }
}
