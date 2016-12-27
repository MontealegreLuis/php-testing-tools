<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Closure;
use Hexagonal\Messaging\MessageConsumer;
use PhpAmqpLib\{Connection\AMQPStreamConnection, Message\AMQPMessage};

class AmqpMessageConsumer implements MessageConsumer
{
    /** @var AMQPStreamConnection */
    private $connection;

    /** @var  \PhpAmqpLib\Channel\AMQPChannel */
    private $channel;

    /** @var callable */
    private $callback;

    /** @var boolean Only consume 1 message */
    private $consumed = false;

    /** @var ChannelConfiguration */
    private $configuration;

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
        $this->configuration->configureQueue($channel, $exchangeName);
        $this->channel = $channel;
    }

    public function consume(string $exchangeName, Closure $callback)
    {
        $this->callback = $callback;
        $this->channel->basic_consume(
            $exchangeName,
            '',
            false,
            true,
            false,
            false,
            Closure::fromCallable([$this, 'callback'])
        );

        while (count($this->channel->callbacks)) {
            if ($this->consumed) {
                break;
            }
            $this->channel->wait(null, false, $idle = 30); // Only wait 30 seconds
        }
    }

    /**
     * @throws \OutOfBoundsException
     */
    public function callback(AMQPMessage $message)
    {
        $callback = $this->callback;

        $callback(json_decode($message->body), $message->get('type'));

        $this->consumed = true;
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
