<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Hexagonal\DataBuilders\A;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

class AmqpMessageConsumerTest extends TestCase
{
    use ConfiguresMessaging;

    /** @var AmqpMessageConsumer */
    private $consumer;

    /** @before */
    function configureChannel()
    {
        $configuration = new ChannelConfiguration();
        $configuration->temporary();
        $this->bindChannel($configuration);
        $this->consumer = new AmqpMessageConsumer($this->connection(), $configuration);
        $this->consumer->open($this->EXCHANGE_NAME);
    }

    /** @test */
    function it_should_consume_a_message()
    {
        $this->publish(A::storedEvent()->build());

        $this->consumer->consume($this->EXCHANGE_NAME, [$this, 'verifyMessage']);
    }

    /**
     * @param stdClass $notification
     */
    public function verifyMessage(stdClass $notification)
    {
        $this->assertObjectHasAttribute('occurred_on', $notification);
        $this->assertObjectHasAttribute('sender_id', $notification);
        $this->assertObjectHasAttribute('amount', $notification);
        $this->assertObjectHasAttribute('recipient_id', $notification);
    }
}
