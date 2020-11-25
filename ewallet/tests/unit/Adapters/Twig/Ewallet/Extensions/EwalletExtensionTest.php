<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Twig\Ewallet\Extensions;

use DataBuilders\A;
use Ewallet\Memberships\MemberFormatter;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class EwalletExtensionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_delegates_formatting_a_money_object()
    {
        $amount = Money::MXN($this->amount);
        $this
            ->formatter
            ->formatMoney($amount)
            ->willReturn(Argument::type('string'))
        ;

        $this->extension->formatMoney($amount);

        $this->formatter->formatMoney($amount)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_delegates_formatting_a_money_amount()
    {
        $this
            ->formatter
            ->formatMoneyAmount($this->amount)
            ->willReturn(Argument::type('string'))
        ;

        $this->extension->formatMoneyAmount($this->amount);

        $this->formatter->formatMoneyAmount($this->amount)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_delegates_formatting_a_member()
    {
        $member = A::member()->build();
        $this
            ->formatter
            ->formatMember($member)
            ->willReturn(Argument::type('string'))
        ;

        $this->extension->formatMember($member);

        $this->formatter->formatMember($member)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_registers_three_twig_simple_functions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(3, $functions);
        $this->assertEquals('member', $functions[0]->getName());
        $this->assertEquals('money_amount', $functions[1]->getName());
        $this->assertEquals('money', $functions[2]->getName());
    }

    /** @before */
    public function configureExtension(): void
    {
        $this->formatter = $this->prophesize(MemberFormatter::class);
        $this->extension = new EwalletExtension($this->formatter->reveal());
    }

    /** @var EwalletExtension */
    private $extension;

    /** @var MemberFormatter */
    private $formatter;

    /** @var int */
    private $amount = 300000;
}
