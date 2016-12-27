<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Hexagonal\{DomainEvents\StoredEvent, Messaging\MessageProducer};
use PhpAmqpLib\{Connection\AMQPStreamConnection, Message\AMQPMessage};

class AmqpMessageProducer implements MessageProducer
{
    /** @var AMQPStreamConnection */
    private $connection;

    /** @var  ChannelConfiguration */
    private $configuration;

    /** @var  \PhpAmqpLib\Channel\AMQPChannel */
    private $channel;

    public function __construct(
        AMQPStreamConnection $connection,
        ChannelConfiguration $configuration
    ) {
        $this->connection = $connection;
        $this->configuration = $configuration;
    }

    public function open(string $exchangeName)
    {
        if (null !== $this->channel) {
            return;
        }

        $channel = $this->connection->channel();
        $this->configuration->configureExchange($channel, $exchangeName);
        $this->configuration->configureQueue($channel, $exchangeName);
        $channel->queue_bind($exchangeName, $exchangeName);
        $this->channel = $channel;
    }

    public function send(string $exchangeName, StoredEvent $notification)
    {
        $this->channel->basic_publish(
            new AMQPMessage($notification->body(), [
                'type' => $notification->type(),
                'timestamp' => $notification->occurredOn()->getTimestamp(),
                'message_id' => $notification->id()
            ]),
            $exchangeName
        );
    }

    /**
     * Close channel and connection
     */
    public function close()
    {
        $this->channel && $this->channel->close();
        $this->connection->close();
    }
}
