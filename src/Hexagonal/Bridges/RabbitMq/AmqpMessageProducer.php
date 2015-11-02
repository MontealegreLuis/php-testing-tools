<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges;

use DateTime;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\Notifications\MessageProducer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessageProducer implements MessageProducer
{
    /** @var AMQPStreamConnection */
    private $connection;

    /** @var  \PhpAmqpLib\Channel\AMQPChannel */
    private $channel;

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
    public function open($exchangeName)
    {
        if (null !== $this->channel) {
            return;
        }

        $channel = $this->connection->channel();
        $channel->exchange_declare($exchangeName, false, false, true, false);
        $channel->queue_declare($exchangeName, false, true, false, false);
        $channel->queue_bind($exchangeName, $exchangeName);
        $this->channel = $channel;
    }

    /**
     * @param string $exchangeName
     * @param StoredEvent $notification
     */
    public function send($exchangeName, StoredEvent $notification)
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
     * @param string $exchangeName
     */
    public function close($exchangeName)
    {
        $this->channel->close();
        $this->connection->close();
    }
}
