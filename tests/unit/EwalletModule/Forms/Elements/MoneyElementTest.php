<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Forms\Elements;

use PHPUnit_Framework_TestCase as TestCase;

class MoneyElementTest extends TestCase
{
    /** @test */
    function it_should_multiply_value_by_100()
    {
        $money = new MoneyElement('amount');

        $money->setValue(100);

        $this->assertEquals(
            ['amount' => 10000, 'currency' => 'MXN'], $money->value()
        );
    }

    /** @test */
    function it_should_format_view_value()
    {
        $money = new MoneyElement('amount');

        $money->setValue(35.33333);

        $view = $money->buildView();

        $this->assertEquals(35.33, $view->amount->value);
    }
}
