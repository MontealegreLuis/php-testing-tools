<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\RabbitMq\Application\Messaging;

use Closure;
use DataBuilders\A;
use Ewallet\Memberships\TransferWasMade;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use RabbitMq\ConfiguresMessaging;

class AmqpMessageProducerTest extends TestCase
{
    use ConfiguresMessaging;

    /** @test */
    function it_should_publish_a_message()
    {
        $this->producer->send(
            $this->EXCHANGE_NAME,
            A::storedEvent()->build()
        );

        $this->consume(Closure::fromCallable(
            [$this, 'assertMessageBodyHasAllRequiredAttributes']
        ));
    }

    public function assertMessageBodyHasAllRequiredAttributes(AMQPMessage $message): void
    {
        $this->stopConsumer();
        $body = json_decode($message->getBody());
        $this->assertEquals(TransferWasMade::class, $message->get('type'));
        $this->assertObjectHasAttribute('occurred_on', $body);
        $this->assertObjectHasAttribute('sender_id', $body);
        $this->assertObjectHasAttribute('amount', $body);
        $this->assertObjectHasAttribute('recipient_id', $body);
    }

    /** @before */
    function configureChannel(): void
    {
        $configuration = ChannelConfiguration::temporary();
        $this->producer = new AmqpMessageProducer($this->connection(), $configuration);
        $this->producer->open($this->EXCHANGE_NAME);
    }

    /** @var AmqpMessageProducer */
    private $producer;
}
