<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms\Elements;

use PHPUnit\Framework\TestCase;

class MoneyElementTest extends TestCase
{
    /** @test */
    function it_sets_its_value_as_a_pair_amount_currency()
    {
        $this->money->setValue(100);

        $this->assertEquals(
            ['amount' => 100, 'currency' => 'MXN'], $this->money->value()
        );
    }

    /** @test */
    function it_passes_value_to_the_amount_text_element()
    {
        $this->money->setValue(3533);

        $view = $this->money->buildView();

        $this->assertEquals(3533, $view->amount->value);
    }

    /** @before */
    public function configureElement(): void
    {
        $this->money = new MoneyElement('amount');
    }

    /** @var MoneyElement */
    private $money;
}
