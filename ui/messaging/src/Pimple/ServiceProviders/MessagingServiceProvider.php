<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Application\Messaging\MessagePublisher;
use Application\Messaging\MessageTracker;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Ports\Doctrine\Application\DomainEvents\EventStoreRepository;
use Ports\Doctrine\Application\Messaging\MessageTrackerRepository;
use Ports\RabbitMq\Application\Messaging\AmqpMessageConsumer;
use Ports\RabbitMq\Application\Messaging\AmqpMessageProducer;
use Ports\RabbitMq\Application\Messaging\ChannelConfiguration;

class MessagingServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[MessageTracker::class] = function () use ($container) {
            return new MessageTrackerRepository($container[EntityManagerInterface::class]);
        };
        $container['hexagonal.amqp_connection'] = function () use ($container) {
            return new AMQPStreamConnection(
                $container['rabbit_mq']['host'],
                $container['rabbit_mq']['port'],
                $container['rabbit_mq']['user'],
                $container['rabbit_mq']['password']
            );
        };
        $container['hexagonal.amqp_configuration'] = function () {
            return ChannelConfiguration::durable();
        };
        $container['hexagonal.messages_producer'] = function () use ($container) {
            return new AmqpMessageProducer(
                $container['hexagonal.amqp_connection'],
                $container['hexagonal.amqp_configuration']
            );
        };
        $container['hexagonal.messages_consumer'] = function () use ($container) {
            return new AmqpMessageConsumer(
                $container['hexagonal.amqp_connection'],
                $container['hexagonal.amqp_configuration']
            );
        };
        $container['hexagonal.messages_publisher'] = function () use ($container) {
            return new MessagePublisher(
                $container[EventStoreRepository::class],
                $container[MessageTracker::class],
                $container['hexagonal.messages_producer']
            );
        };
    }
}
