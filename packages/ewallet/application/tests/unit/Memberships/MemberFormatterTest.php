<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Ewallet\DataBuilders\A;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class MemberFormatterTest extends TestCase
{
    /** @test */
    function it_shows_two_decimal_places()
    {
        $this->assertEquals('234.25', $this->formatter->formatMoneyAmount(234.253));
        $this->assertEquals('234.20', $this->formatter->formatMoneyAmount(234.2));
    }

    /** @test */
    function it_adds_thousands_separator_to_a_money_amount()
    {
        $this->assertEquals('1,234.25', $this->formatter->formatMoneyAmount(1234.25));
    }

    /** @test */
    function it_adds_2_decimal_places_to_an_integer_money_amount()
    {
        $this->assertEquals('546.00', $this->formatter->formatMoneyAmount(546));
    }

    /** @test */
    function it_formats_a_money_object_information()
    {
        $this->assertEquals(
            '$5,987.29 MXN',
            $this->formatter->formatMoney(Money::MXN(598729))
        );
    }

    /** @test */
    function it_formats_a_member_information()
    {
        $aMember = A::member()
            ->named('Mario Montealegre')
            ->withBalance(1500025)
            ->build()
        ;

        $this->assertEquals(
            'Mario Montealegre $15,000.25 MXN',
            $this->formatter->formatMember($aMember->information())
        );
    }

    /** @before */
    public function createFormatter(): void
    {
        $this->formatter = new MemberFormatter();
    }

    /** @var MemberFormatter */
    private $formatter;
}
