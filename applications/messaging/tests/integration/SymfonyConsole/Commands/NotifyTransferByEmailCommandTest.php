<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Accounts\TransferWasMade;
use Ewallet\Wallet\Notifications\{TransferFundsEmailNotifier, TransferFundsNotification};
use Hexagonal\DataBuilders\A;
use Hexagonal\RabbitMq\{AmqpMessageConsumer, ChannelConfiguration};
use Mockery;
use PhpAmqpLib\{Connection\AMQPStreamConnection, Message\AMQPMessage};
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class NotifyTransferByEmailCommandTest extends TestCase
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
        $this->channel = $channel;
        $this->consumer = new AmqpMessageConsumer($this->connection, $configuration);
    }

    /** @test */
    function it_notifies_when_a_transfer_is_completed()
    {
        $notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with(TransferWasMade::class)
            ->andReturn(true)
        ;
        $notifier
            ->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(TransferFundsNotification::class))
        ;
        $notification = A::storedEvent()->build();
        $this->channel->basic_publish(
            new AMQPMessage($notification->body(), [
                'type' => $notification->type(),
                'timestamp' => $notification->occurredOn()->getTimestamp(),
                'message_id' => $notification->id()
            ]),
            'test'
        );

        $tester = new CommandTester(
            new NotifyTransferByEmailCommand($notifier, $this->consumer, 'test')
        );
        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /** @test */
    function it_ignores_events_different_to_transfer_completed()
    {
        $notifier = Mockery::mock(TransferFundsEmailNotifier::class);
        $notifier
            ->shouldReceive('shouldNotifyOn')
            ->once()
            ->with('Ewallet\Foo')
            ->andReturn(false)
        ;
        $notifier->shouldNotReceive('notify');
        $this->channel->basic_publish(
            new AMQPMessage('{"foo": "bar"}', [
                'type' => 'Ewallet\Foo', // Not a transfer
                'timestamp' => "123123",
                'message_id' => "any id"
            ]),
            'test'
        );

        $tester = new CommandTester(
            new NotifyTransferByEmailCommand($notifier, $this->consumer, 'test')
        );
        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /** @after */
    public function closeChannel()
    {
        $this->connection && $this->connection->close();
        $this->channel && $this->channel->close();
    }
}
