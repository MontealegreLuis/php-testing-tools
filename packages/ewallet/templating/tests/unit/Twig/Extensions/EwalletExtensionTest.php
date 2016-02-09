<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace Ewallet\Twig\Extensions;

use Ewallet\DataBuilders\A;
use Ewallet\Presenters\MemberFormatter;
use Mockery;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class EwalletExtensionTest extends TestCase
{
    /** @var MemberFormatter */
    private $formatter;

    /** @var EwalletExtension */
    private $extension;

    /** @before */
    public function configureExtension()
    {
        $this->formatter = Mockery::mock(MemberFormatter::class)->shouldIgnoreMissing();
        $this->extension = new EwalletExtension($this->formatter);
    }

    /** @test */
    function it_should_delegate_formatting_a_money_object()
    {
        $this->extension->formatMoney($amount = Money::MXN(300000));

        $this->formatter
            ->shouldHaveReceived('formatMoney')
            ->once()
            ->with($amount)
        ;
    }

    /** @test */
    function it_should_delegate_formatting_a_money_amount()
    {
        $this->extension->formatMoneyAmount($amount = 300000);

        $this->formatter
            ->shouldHaveReceived('formatMoneyAmount')
            ->once()
            ->with($amount)
        ;
    }

    function it_should_delegate_formatting_a_member()
    {
        $member = A::member()->build()->information();

        $this->extension->formatMember($member);

        $this->formatter
            ->shouldHaveReceived('formatMember')
            ->once()
            ->with($member)
        ;
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
