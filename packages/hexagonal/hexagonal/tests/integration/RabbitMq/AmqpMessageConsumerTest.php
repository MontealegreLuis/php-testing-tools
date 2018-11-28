<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use Closure;
use Hexagonal\DataBuilders\A;
use PHPUnit\Framework\TestCase;
use Ports\Messaging\RabbitMq\AmqpMessageConsumer;
use Ports\Messaging\RabbitMq\ChannelConfiguration;
use stdClass;

class AmqpMessageConsumerTest extends TestCase
{
    use ConfiguresMessaging;

    /** @test */
    function it_should_consume_a_message()
    {
        $this->publish(A::storedEvent()->build());

        $this->consumer->consume(
            $this->EXCHANGE_NAME,
            Closure::fromCallable([$this, 'assertObjectHasRequiredAttributes'])
        );
    }

    public function assertObjectHasRequiredAttributes(stdClass $notification): void
    {
        $this->assertObjectHasAttribute('occurred_on', $notification);
        $this->assertObjectHasAttribute('sender_id', $notification);
        $this->assertObjectHasAttribute('amount', $notification);
        $this->assertObjectHasAttribute('recipient_id', $notification);
    }

    /** @before */
    function configureChannel(): void
    {
        $configuration = ChannelConfiguration::temporary();
        $this->bindChannel($configuration);
        $this->consumer = new AmqpMessageConsumer($this->connection(), $configuration);
        $this->consumer->open($this->EXCHANGE_NAME);
    }

    /** @var AmqpMessageConsumer */
    private $consumer;
}
