<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\RabbitMq\Application\Messaging;

use Application\DomainEvents\StoredEvent;
use Application\Messaging\MessageProducer;
use BadMethodCallException;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessageProducer implements MessageProducer
{
    /** @var AMQPStreamConnection */
    private $connection;

    /** @var  ChannelConfiguration */
    private $configuration;

    /** @var  \PhpAmqpLib\Channel\AMQPChannel|null */
    private $channel;

    public function __construct(
        AMQPStreamConnection $connection,
        ChannelConfiguration $configuration
    ) {
        $this->connection = $connection;
        $this->configuration = $configuration;
    }

    public function open(string $exchangeName): void
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

    public function send(string $exchangeName, StoredEvent $notification): void
    {
        if ($this->channel === null) {
            throw new BadMethodCallException('No channel has been configure, call AmqpMessageProducer::open first');
        }

        $this->channel->basic_publish(
            new AMQPMessage($notification->body(), [
                'type' => $notification->type(),
                'timestamp' => $notification->occurredOn()->getTimestamp(),
                'message_id' => $notification->id(),
            ]),
            $exchangeName
        );
    }

    /**
     * Close channel and connection
     */
    public function close(): void
    {
        if ($this->channel !== null) {
            $this->channel->close();
        }
        $this->connection->close();
    }
}
