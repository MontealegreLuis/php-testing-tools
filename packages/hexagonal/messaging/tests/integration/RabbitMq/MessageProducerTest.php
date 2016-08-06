<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Ewallet\Accounts\TransferWasMade;
use Hexagonal\DataBuilders\A;
use PhpAmqpLib\{Connection\AMQPStreamConnection, Message\AMQPMessage};
use PHPUnit_Framework_TestCase as TestCase;

class MessageProducerTest extends TestCase
{
    /** @var \PhpAmqpLib\Channel\AMQPChannel */
    private $channel;

    /** @var AmqpMessageProducer */
    private $producer;

    /** @var AMQPStreamConnection */
    private $connection;

    /** @var bool */
    private $consumed = false;

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
        $this->producer = new AmqpMessageProducer($this->connection, $configuration);
        $this->producer->open('test');
        $this->channel = $this->connection->channel();
    }

    /** @test */
    function it_should_publish_a_message()
    {
        $this->producer->send('test', A::storedEvent()->withId(234)->build());

        $this->channel->basic_consume(
            'test',
            '',
            false,
            true,
            false,
            false,
            [$this, 'processMsg']
        );
        while (count($this->channel->callbacks)) {
            if ($this->consumed) {
                break;
            }

            $this->channel->wait(null, false, $idle = 30);
        }
    }

    public function processMsg(AMQPMessage $message)
    {
        $this->consumed = true;
        $body = json_decode($message->getBody());
        $this->assertEquals(TransferWasMade::class, $message->get('type'));
        $this->assertObjectHasAttribute('occurred_on', $body);
        $this->assertObjectHasAttribute('from_member_id', $body);
        $this->assertObjectHasAttribute('amount', $body);
        $this->assertObjectHasAttribute('to_member_id', $body);
    }

    /** @after */
    public function closeChannel()
    {
        $this->connection && $this->connection->close();
        $this->channel && $this->channel->close();
    }
}
