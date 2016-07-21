<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Wallet\Notifications\TransferFundsEmailNotifier;
use Hexagonal\Doctrine2\DomainEvents\EventStoreRepository;
use Hexagonal\Doctrine2\Messaging\MessageTrackerRepository;
use Hexagonal\Messaging\MessagePublisher;
use Hexagonal\RabbitMq\{AmqpMessageConsumer, AmqpMessageProducer};
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit_Framework_TestCase as TestCase;
use Pimple\Container;

class EwalletMessagingServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_the_service_for_the_messaging_application()
    {
        $options = require __DIR__ . '/../../../../config.tests.php';
        $container = new Container($options);
        $container->register(new TwigServiceProvider());
        $container->register(new DoctrineServiceProvider());
        $container->register(new HexagonalServiceProvider());
        $container->register(new EwalletMessagingServiceProvider());
        $this->assertInstanceOf(
            TransferFundsEmailNotifier::class,
            $container['ewallet.transfer_mail_notifier']
        );
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
    }
}
