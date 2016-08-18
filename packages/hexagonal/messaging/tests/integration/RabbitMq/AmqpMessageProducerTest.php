<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Ewallet\Accounts\TransferWasMade;
use Hexagonal\DataBuilders\A;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit_Framework_TestCase as TestCase;

class AmqpMessageProducerTest extends TestCase
{
    use ConfiguresMessaging;

    /** @var AmqpMessageProducer */
    private $producer;

    /** @before */
    function configureChannel()
    {
        $configuration = new ChannelConfiguration();
        $configuration->temporary();
        $this->producer = new AmqpMessageProducer($this->connection(), $configuration);
        $this->producer->open($this->EXCHANGE_NAME);
    }

    /** @test */
    function it_should_publish_a_message()
    {
        $this->producer->send(
            $this->EXCHANGE_NAME,
            A::storedEvent()->withId(234)->build()
        );

        $this->consume([$this, 'verifyMessage']);
    }

    /**
     * @param AMQPMessage $message
     */
    public function verifyMessage(AMQPMessage $message)
    {
        $this->stopConsumer();
        $body = json_decode($message->getBody());
        $this->assertEquals(TransferWasMade::class, $message->get('type'));
        $this->assertObjectHasAttribute('occurred_on', $body);
        $this->assertObjectHasAttribute('sender_id', $body);
        $this->assertObjectHasAttribute('amount', $body);
        $this->assertObjectHasAttribute('recipient_id', $body);
    }
}
