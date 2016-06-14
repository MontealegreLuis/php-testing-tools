<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Twig\Extensions;

use Ewallet\{DataBuilders\A, Presenters\MemberFormatter};
use Mockery;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class EwalletExtensionTest extends TestCase
{
    /** @var MemberFormatter */
    private $formatter;

    /** @var EwalletExtension */
    private $extension;

    /** @var int */
    private $amount = 300000;

    /** @before */
    public function configureExtension()
    {
        $this->formatter = Mockery::mock(MemberFormatter::class)->shouldIgnoreMissing();
        $this->extension = new EwalletExtension($this->formatter);
    }

    /** @test */
    function it_delegates_formatting_a_money_object()
    {
        $amount = Money::MXN($this->amount);

        $this->formatter
            ->shouldReceive('formatMoney')
            ->once()
            ->with($amount)
            ->andReturn("\${$this->amount}.00 MXN")
        ;

        $this->extension->formatMoney($amount);
    }

    /** @test */
    function it_should_delegate_formatting_a_money_amount()
    {
        $this->formatter
            ->shouldReceive('formatMoneyAmount')
            ->once()
            ->with($this->amount)
            ->andReturn("{$this->amount}.00")
        ;
        $this->extension->formatMoneyAmount($this->amount);
    }

    /** @test */
    function it_should_delegate_formatting_a_member()
    {
        $member = A::member()->build()->information();
        $this->formatter
            ->shouldReceive('formatMember')
            ->once()
            ->with($member)
        ;

        $this->extension->formatMember($member);
    }

    /** @test */
    function it_should_register_three_twig_simple_functions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(3, $functions);
        $this->assertEquals('member', $functions[0]->getName());
        $this->assertEquals('money_amount', $functions[1]->getName());
        $this->assertEquals('money', $functions[2]->getName());
    }
}
