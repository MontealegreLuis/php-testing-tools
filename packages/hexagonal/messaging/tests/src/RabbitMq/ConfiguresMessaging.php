<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Closure;
use Hexagonal\DomainEvents\StoredEvent;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Publishes and Consumes messages to help the creation of integration tests
 * for RabbitMQ
 */
trait ConfiguresMessaging
{
    /** @var string */
    protected $EXCHANGE_NAME = 'test';

    /** @var bool */
    private $consumed = false;

    /** @var AMQPStreamConnection */
    private $connection;

    /** @var AMQPChannel */
    private $channel;

    /**
     * Configures the channel before publishing
     */
    public function bindChannel(ChannelConfiguration $configuration): void
    {
        $channel = $this->channel();
        $configuration->configureExchange($channel, $this->EXCHANGE_NAME);
        $configuration->configureQueue($channel, $this->EXCHANGE_NAME);
        $channel->queue_bind($this->EXCHANGE_NAME, $this->EXCHANGE_NAME);
    }

    public function publish(StoredEvent $notification): void
    {
        $this->channel()->basic_publish(
            new AMQPMessage($notification->body(), [
                'type' => $notification->type(),
                'timestamp' => $notification->occurredOn()->getTimestamp(),
                'message_id' => $notification->id()
            ]),
            $this->EXCHANGE_NAME
        );
    }

    /**
     * @param Closure $testCase Runs the assertions to verify the correctness of
     * the message being consumed
     *
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     * @throws \PhpAmqpLib\Exception\AMQPRuntimeException
     */
    public function consume(Closure $testCase): void
    {
        $this->channel()->basic_consume(
            $this->EXCHANGE_NAME,
            '',
            false,
            true,
            false,
            false,
            $testCase
        );
        while (count($this->channel()->callbacks)) {
            if ($this->consumed) {
                break;
            }

            $this->channel()->wait(null, false, $idle = 30);
        }
    }

    /**
     * Stop the consumer triggered by the call the method `consume` in this class
     */
    public function stopConsumer(): void
    {
        $this->consumed = true;
    }

    private function channel(): AMQPChannel
    {
        if (!$this->channel) {
            $this->channel = $this->connection()->channel();
        }
        return $this->channel;
    }

    public function connection(): AMQPStreamConnection
    {
        if (!$this->connection) {
            $this->connection = new AMQPStreamConnection(
                getenv('RABBIT_MQ_HOST'),
                5672,
                getenv('RABBIT_MQ_USER'),
                getenv('RABBIT_MQ_PASSWORD')
            );
        }

        return $this->connection;
    }

    /** @after */
    public function closeChannel(): void
    {
        $this->connection && $this->connection->close();
        $this->channel && $this->channel->close();
    }
}
