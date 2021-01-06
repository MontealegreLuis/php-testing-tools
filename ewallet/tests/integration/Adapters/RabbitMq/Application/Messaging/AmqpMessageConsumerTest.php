<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\RabbitMq\Application\Messaging;

use Closure;
use DataBuilders\A;
use PHPUnit\Framework\TestCase;
use RabbitMq\ConfiguresMessaging;
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
    function let()
    {
        $configuration = ChannelConfiguration::temporary();
        $this->bindChannel($configuration);
        $this->consumer = new AmqpMessageConsumer($this->connection(), $configuration);
        $this->consumer->open($this->EXCHANGE_NAME);
    }

    private AmqpMessageConsumer $consumer;
}
