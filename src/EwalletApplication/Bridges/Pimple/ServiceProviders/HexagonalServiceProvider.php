<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Hexagonal\Bridges\RabbitMq\AmqpMessageProducer;
use Hexagonal\Bridges\RabbitMq\AmqpMessageConsumer;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\Messaging\MessagePublisher;
use Hexagonal\Messaging\PublishedMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HexagonalServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['hexagonal.event_store_repository'] = function () use ($container) {
            return $container['doctrine.em']->getRepository(StoredEvent::class);
        };
        $container['hexagonal.message_tracker_repository'] = function () use ($container) {
            return $container['doctrine.em']->getRepository(PublishedMessage::class);
        };
        $container['hexagonal.amqp_connection'] = function () use ($container) {
            return new AMQPStreamConnection(
                $container['rabbit_mq']['host'],
                $container['rabbit_mq']['port'],
                $container['rabbit_mq']['user'],
                $container['rabbit_mq']['password']
            );
        };
        $container['hexagonal.messages_producer'] = function () use ($container) {
            return new AmqpMessageProducer(
                $container['hexagonal.amqp_connection']
            );
        };
        $container['hexagonal.messages_consumer'] = function () use ($container) {
            return new AmqpMessageConsumer(
                $container['hexagonal.amqp_connection']
            );
        };
        $container['hexagonal.messages_publisher'] = function () use ($container) {
            return new MessagePublisher(
                $container['hexagonal.event_store_repository'],
                $container['hexagonal.message_tracker_repository'],
                $container['hexagonal.messages_producer']
            );
        };
    }
}
