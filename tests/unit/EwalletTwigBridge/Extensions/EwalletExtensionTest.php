<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace EwalletTwigBridge\Extensions;

use EwalletTestsBridge\MembersBuilder;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class EwalletExtensionTest extends TestCase
{
    /** @test */
    function it_should_render_a_money_amount()
    {
        $extension = new EwalletExtension();
        $amount = Money::MXN(300000);

        $this->assertEquals('$3,000.00 MXN', $extension->renderMoney($amount));
    }

    /** @test */
    function it_should_render_a_member()
    {
        $extension = new EwalletExtension();
        $member = MembersBuilder::aMember()
            ->withName('Luis Montealegre')
            ->withBalance(255000)
            ->build();

        $this->assertEquals(
            'Luis Montealegre $2,550.00 MXN',
            $extension->renderMember($member->information())
        );
    }
}
