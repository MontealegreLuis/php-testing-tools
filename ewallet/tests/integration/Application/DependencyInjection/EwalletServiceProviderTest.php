<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Adapters\Pimple\Application\DependencyInjection\DoctrineServiceProvider;
use Adapters\Pimple\Application\DependencyInjection\EwalletServiceProvider;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\Members;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class EwalletServiceProviderTest extends TestCase
{
    /** @test */
    function it_should_create_ewallet_shared_services()
    {
        $options = require __DIR__ . '/../../../../config/config.php';
        $container = new Container($options);
        $container->register(new DoctrineServiceProvider());
        $container->register(new EwalletServiceProvider());

        $this->assertInstanceOf(Members::class, $container[Members::class]);
        $this->assertInstanceOf(TransferFundsAction::class, $container[TransferFundsAction::class]);
    }
}
