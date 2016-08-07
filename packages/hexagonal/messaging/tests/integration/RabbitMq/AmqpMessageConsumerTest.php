<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Hexagonal\DataBuilders\A;
use PhpAmqpLib\{Connection\AMQPStreamConnection, Message\AMQPMessage};
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

class AmqpMessageConsumerTest extends TestCase
{
    /** @var \PhpAmqpLib\Channel\AMQPChannel */
    private $channel;

    /** @var AmqpMessageConsumer */
    private $consumer;

    /** @var AMQPStreamConnection */
    private $connection;

    /** @before */
    function configureChannel()
    {
        $this->connection = new AMQPStreamConnection(
            getenv('RABBIT_MQ_HOST'),
            5672,
            getenv('RABBIT_MQ_USER'),
            getenv('RABBIT_MQ_PASSWORD')
        );
        $configuration = new ChannelConfiguration();
        $configuration->temporary();
        $channel = $this->connection->channel();
        $configuration->configureExchange($channel, 'test');
        $configuration->configureQueue($channel, 'test');
        $channel->queue_bind('test', 'test');
        $this->consumer = new AmqpMessageConsumer($this->connection, $configuration);
        $this->consumer->open('test');
        $this->channel = $channel;
    }

    /** @test */
    function it_should_consume_a_message()
    {
        $notification = A::storedEvent()->build();
        $this->channel->basic_publish(
            new AMQPMessage($notification->body(), [
                'type' => $notification->type(),
                'timestamp' => $notification->occurredOn()->getTimestamp(),
                'message_id' => $notification->id()
            ]),
            'test'
        );

        $this->consumer->consume('test', [$this, 'verifyMessage']);
    }

    /**
     * @param stdClass $notification
     */
    public function verifyMessage(stdClass $notification)
    {
        $this->assertObjectHasAttribute('occurred_on', $notification);
        $this->assertObjectHasAttribute('from_member_id', $notification);
        $this->assertObjectHasAttribute('amount', $notification);
        $this->assertObjectHasAttribute('to_member_id', $notification);
    }

    /** @after */
    public function closeChannel()
    {
        $this->connection && $this->connection->close();
        $this->channel && $this->channel->close();
    }
}
