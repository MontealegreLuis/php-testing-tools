<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\RabbitMq\Application\Messaging;

use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;

class ChannelConfigurationTest extends TestCase
{
    /** @test */
    function it_configures_a_temporary_exchange()
    {
        $nonDurable = false;
        $autoDelete = true;

        $configuration = ChannelConfiguration::temporary();
        $configuration->configureExchange($this->channel->reveal(), $this->exchangeName);

        $this->channel
            ->exchange_declare($this->exchangeName, 'fanout', false, $nonDurable, $autoDelete)
            ->shouldHaveBeenCalled();
    }

    /** @test */
    function it_configures_a_durable_exchange()
    {
        $durable = true;
        $doNotDelete = false;

        $configuration = ChannelConfiguration::durable();
        $configuration->configureExchange($this->channel->reveal(), $this->exchangeName);

        $this->channel
            ->exchange_declare($this->exchangeName, 'fanout', false, $durable, $doNotDelete)
            ->shouldHaveBeenCalled();
    }

    /** @test */
    function it_configures_a_temporary_queue()
    {
        $nonDurable = false;
        $autoDelete = true;

        $configuration = ChannelConfiguration::temporary();
        $configuration->configureQueue($this->channel->reveal(), $this->exchangeName);

        $this->channel
            ->queue_declare($this->exchangeName, false, $nonDurable, false, $autoDelete)
            ->shouldHaveBeenCalled();
    }

    /** @test */
    function it_configures_a_durable_queue()
    {
        $durable = true;
        $doNotDelete = false;

        $configuration = ChannelConfiguration::durable();
        $configuration->configureQueue($this->channel->reveal(), $this->exchangeName);

        $this->channel
            ->queue_declare($this->exchangeName, false, $durable, false, $doNotDelete)
            ->shouldHaveBeenCalled();
    }

    /** @before */
    public function configureDoubles(): void
    {
        $this->channel = $this->prophesize(AMQPChannel::class);
    }

    /** @var AMQPChannel */
    private $channel;

    /** @var string */
    private $exchangeName = 'test_exchange';
}
