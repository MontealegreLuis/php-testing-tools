<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Memberships\DoctrineMembers;
use Ewallet\EasyForms\MembersConfiguration;
use Ewallet\Memberships\MemberFormatter;
use Ewallet\ManageWallet\TransferFundsTransactionally;
use Ewallet\ManageWallet\TransferWasMadeLogger;
use Ewallet\Zf2\InputFilter\TransferFundsInputFilter;
use Monolog\Logger;
use PHPUnit_Framework_TestCase as TestCase;
use Pimple\Container;

/**
 * Tests that the shared objects used by the console, web and messaging
 * applications are created correctly. These shared objects include definitions
 * from the following packages ewallet/doctrine, ewallet/responder,
 * ewallet/application and ewallet/validation.
 */
class EwalletServiceProviderTest extends TestCase
{
    /** @test */
    function it_should_create_ewallet_shared_services()
    {
        $options = require __DIR__ . '/../../../../config.php';
        $container = new Container($options);
        $container->register(new DoctrineServiceProvider());
        $container->register(new EwalletServiceProvider());

        $this->assertInstanceOf(
            DoctrineMembers::class,
            $container['ewallet.member_repository']
        );
        $this->assertInstanceOf(
            MembersConfiguration::class,
            $container['ewallet.members_configuration']
        );
        $this->assertInstanceOf(
            MemberFormatter::class,
            $container['ewallet.member_formatter']
        );
        $this->assertInstanceOf(
            TransferFundsTransactionally::class,
            $container['ewallet.transfer_funds']
        );
        $this->assertInstanceOf(
            Logger::class,
            $container['ewallet.logger']
        );
        $this->assertInstanceOf(
            TransferWasMadeLogger::class,
            $container['ewallet.transfer_funds_logger']
        );
        $this->assertInstanceOf(
            TransferFundsInputFilter::class,
            $container['ewallet.transfer_filter_request']
        );
    }
}
