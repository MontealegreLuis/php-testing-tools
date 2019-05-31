<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Application\Messaging\MessagePublisher;
use Application\Messaging\MessageTracker;
use Ewallet\ManageWallet\Notifications\TransferFundsEmailNotifier;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Adapters\Doctrine\Application\DomainEvents\EventStoreRepository;
use Adapters\Doctrine\Application\Messaging\MessageTrackerRepository;
use Adapters\Pimple\Application\DependencyInjection\DoctrineServiceProvider;
use Adapters\Pimple\Application\DependencyInjection\TwigServiceProvider;
use Adapters\RabbitMq\Application\Messaging\AmqpMessageConsumer;
use Adapters\RabbitMq\Application\Messaging\AmqpMessageProducer;

class EwalletMessagingServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_the_services_for_the_messaging_application()
    {
        $options = require __DIR__ . '/../../../../config.tests.php';
        $container = new Container($options);
        $container->register(new TwigServiceProvider());
        $container->register(new DoctrineServiceProvider());
        $container->register(new MessagingServiceProvider());
        $container->register(new EwalletMessagingServiceProvider());
        $this->assertInstanceOf(
            TransferFundsEmailNotifier::class,
            $container['ewallet.transfer_mail_notifier']
        );
        $this->assertInstanceOf(
            EventStoreRepository::class,
            $container[EventStoreRepository::class]
        );
        $this->assertInstanceOf(
            MessageTrackerRepository::class,
            $container[MessageTracker::class]
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
