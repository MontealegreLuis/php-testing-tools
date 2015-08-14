<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple;

use Doctrine\ORM\EntityManager;
use Ewallet\Bridges\Doctrine2\Accounts\MembersRepository;
use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletModule\Bridges\Twig\Extensions\EwalletExtension;
use PHPUnit_Framework_TestCase as TestCase;

class EwalletContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_console_application_services()
    {
        $options = require __DIR__ . '/../../../../../app/config.php';
        $container = new EwalletContainer($options);

        $this->assertInstanceOf(
            EntityManager::class,
            $container['doctrine.em']
        );
        $this->assertInstanceOf(
            MembersRepository::class,
            $container['ewallet.member_repository']
        );
        $this->assertInstanceOf(
            EwalletExtension::class,
            $container['ewallet.twig.extension']
        );
        $this->assertInstanceOf(
            TransferFundsTransactionally::class,
            $container['ewallet.transfer_funds']
        );
    }
}
