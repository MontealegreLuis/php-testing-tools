<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

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

    /**
     * AmqpMessageProducer constructor.
     * @param $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $exchangeName
     */
    public function open(string $exchangeName)
    {
        if (null !== $this->channel) {
            return;
        }

        $channel = $this->connection->channel();
        $channel->queue_declare($exchangeName, false, true, false, false);
        $this->channel = $channel;
    }

    /**
     * @param string $exchangeName
     * @param callable $callback
     */
    public function consume(string $exchangeName, callable $callback)
    {
        $this->callback = $callback;
        $this->channel->basic_consume(
            $exchangeName, '', false, true, false, false, [$this, 'callback']
        );

        while (count($this->channel->callbacks)) {
            if ($this->consumed) {
                break;
            }
            $this->channel->wait(null, false, $idle = 30); // Only wait 30 seconds
        }
    }

    /**
     * @param AMQPMessage $message
     */
    public function callback(AMQPMessage $message)
    {
        call_user_func_array($this->callback, [
            json_decode($message->body),
            $message->get('type')
        ]);
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
