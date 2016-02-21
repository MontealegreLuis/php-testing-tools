<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use Ewallet\Doctrine2\Accounts\DoctrineMembers;
use Ewallet\Wallet\TransferFundsTransactionally;
use Ewallet\Actions\TransferFundsAction;
use Ewallet\EasyForms\MembersConfiguration;
use Ewallet\Listeners\LogTransferWasMadeSubscriber;
use Ewallet\Responders\TransferFundsConsoleResponder;
use Ewallet\Zf2\InputFilter\TransferFundsInputFilterRequest;
use Ewallet\Presenters\MemberFormatter;
use Hexagonal\Doctrine2\DomainEvents\EventStoreRepository;
use Hexagonal\Doctrine2\Messaging\MessageTrackerRepository;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\Messaging\MessagePublisher;
use Monolog\Logger;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class EwalletConsoleContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_console_application_services()
    {
        $environment = new Dotenv(__DIR__ . '/../../../');
        $environment->load();
        $options = require __DIR__ . '/../../../config.php';
        $container = new EwalletConsoleContainer($options);

        $this->assertInstanceOf(
            EntityManager::class,
            $container['doctrine.em']
        );
        $this->assertInstanceOf(
            DoctrineMembers::class,
            $container['ewallet.member_repository']
        );
        /*
        $this->assertInstanceOf(
            MembersConfiguration::class,
            $container['ewallet.members_configuration']
        );
        */
        $this->assertInstanceOf(
            MemberFormatter::class,
            $container['ewallet.member_formatter']
        );
        $this->assertInstanceOf(
            TransferFundsInputFilterRequest::class,
            $container['ewallet.transfer_filter_request']
        );
        $this->assertInstanceOf(
            TransferFundsTransactionally::class,
            $container['ewallet.transfer_funds']
        );
        // console objects: 3
        $this->assertInstanceOf(
            ArgvInput::class,
            $container['ewallet.console_input']
        );
        $this->assertInstanceOf(
            ConsoleOutput::class,
            $container['ewallet.console_output']
        );
        $this->assertInstanceOf(
            TransferFundsConsoleResponder::class,
            $container['ewallet.transfer_funds_console_responder']
        );
        $this->assertInstanceOf(
            TransferFundsAction::class,
            $container['ewallet.transfer_funds_console_action']
        );
        $this->assertInstanceOf(
            Logger::class,
            $container['ewallet.logger']
        );
        $this->assertInstanceOf(
            LogTransferWasMadeSubscriber::class,
            $container['ewallet.transfer_funds_logger']
        );
        /*
        $this->assertInstanceOf(
            TransferFundsEmailNotifier::class,
            $container['ewallet.transfer_mail_notifier']
        );
        */
        $this->assertInstanceOf(
            EventPublisher::class,
            $container['ewallet.events_publisher']
        );
        /*
        $this->assertInstanceOf(
            EventStoreRepository::class,
            $container['hexagonal.event_store_repository']
        );
        $this->assertInstanceOf(
            MessageTrackerRepository::class,
            $container['hexagonal.message_tracker_repository']
        );
        $this->assertInstanceOf(
            AMQPStreamConnection::class,
            $connection = $container['hexagonal.amqp_connection']
        );
        $connection->close();
        $this->assertInstanceOf(
            AmqpMessageProducer::class,
            $producer = $container['hexagonal.messages_producer']
        );
        $producer->close();
        $this->assertInstanceOf(
            AmqpMessageConsumer::class,
            $consumer = $container['hexagonal.messages_consumer']
        );
        $consumer->close();
        $this->assertInstanceOf(
            MessagePublisher::class,
            $container['hexagonal.messages_publisher']
        );
        */
    }
}
