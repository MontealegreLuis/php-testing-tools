<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple;

use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use Ewallet\Bridges\Doctrine2\Accounts\MembersRepository;
use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletModule\Actions\EventSubscribers\EmailTransferWasMadeSubscriber;
use EwalletModule\Bridges\Monolog\LogTransferWasMadeSubscriber;
use EwalletModule\View\MemberFormatter;
use Hexagonal\DomainEvents\EventPublisher;
use Monolog\Logger;
use PHPUnit_Framework_TestCase as TestCase;

class EwalletConsoleContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_console_application_services()
    {
        $environment = new Dotenv(__DIR__ . '/../../../../../');
        $environment->load();
        $options = require __DIR__ . '/../../../../../app/config_dev.php';
        $container = new EwalletConsoleContainer($options);

        $this->assertInstanceOf(
            EntityManager::class,
            $container['doctrine.em']
        );
        $this->assertInstanceOf(
            MembersRepository::class,
            $container['ewallet.member_repository']
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
            LogTransferWasMadeSubscriber::class,
            $container['ewallet.transfer_funds_logger']
        );
        $this->assertInstanceOf(
            EmailTransferWasMadeSubscriber::class,
            $container['ewallet.transfer_mail_notifier']
        );
        $this->assertInstanceOf(
            EventPublisher::class,
            $container['ewallet.events_publisher']
        );
    }
}