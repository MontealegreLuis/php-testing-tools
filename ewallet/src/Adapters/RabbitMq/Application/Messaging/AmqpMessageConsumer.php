<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\RabbitMq\Application\Messaging;

use Application\Messaging\MessageConsumer;
use BadMethodCallException;
use Closure;
use OutOfBoundsException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class AmqpMessageConsumer implements MessageConsumer
{
    private ChannelConfiguration $configuration;

    private AMQPStreamConnection $connection;

    private ?AMQPChannel $channel = null;

    private ?Closure $callback = null;

    /** Consume only 1 message */
    private bool $consumed = false;

    public function __construct(AMQPStreamConnection $connection, ChannelConfiguration $configuration)
    {
        $this->connection = $connection;
        $this->configuration = $configuration;
    }

    public function open(string $exchangeName): void
    {
        if ($this->channel !== null) {
            return;
        }

        $channel = $this->connection->channel();
        $this->configuration->configureQueue($channel, $exchangeName);
        $this->channel = $channel;
    }

    public function consume(string $exchangeName, Closure $callback): void
    {
        if ($this->channel === null) {
            throw new BadMethodCallException('No channel has been configure, call AmqpMessageConsumer::open first');
        }

        $this->callback = $callback;
        $this->channel->basic_consume(
            $exchangeName,
            '',
            false,
            true,
            false,
            false,
            Closure::fromCallable(function (AMQPMessage $message): void {
                $this->callback($message);
            })
        );

        while (count($this->channel->callbacks)) {
            if ($this->consumed) {
                break;
            }
            $this->channel->wait(null, false, $idle = 30); // Only wait 30 seconds
        }
    }

    /**
     * @throws OutOfBoundsException
     */
    public function callback(AMQPMessage $message): void
    {
        if (! is_callable($this->callback)) {
            throw new InvalidConsumerCallback();
        }
        $callback = $this->callback;

        $callback(json_decode($message->body), $message->get('type'));

        $this->consumed = true;
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
