<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit_Framework_TestCase as TestCase;
use Mockery;

class ChannelConfigurationTest extends TestCase
{
    /** @test */
    function it_configures_a_temporary_exchange()
    {
        $nonDurable = false;
        $autoDelete = true;

        $configuration = ChannelConfiguration::temporary();
        $configuration->configureExchange($this->channel, $this->exchangeName);

        $this->channel
            ->shouldHaveReceived('exchange_declare')
            ->with($this->exchangeName, 'fanout', false, $nonDurable, $autoDelete)
        ;
    }

    /** @test */
    function it_configures_a_durable_exchange()
    {
        $durable = true;
        $doNotDelete = false;

        $configuration = ChannelConfiguration::durable();
        $configuration->configureExchange($this->channel, $this->exchangeName);

        $this->channel
            ->shouldHaveReceived('exchange_declare')
            ->with($this->exchangeName, 'fanout', false, $durable, $doNotDelete)
        ;
    }

    /** @test */
    function it_configures_a_temporary_queue()
    {
        $nonDurable = false;
        $autoDelete = true;

        $configuration = ChannelConfiguration::temporary();
        $configuration->configureQueue($this->channel, $this->exchangeName);

        $this->channel
            ->shouldHaveReceived('queue_declare')
            ->with($this->exchangeName, false, $nonDurable, false, $autoDelete)
        ;
    }

    /** @test */
    function it_configures_a_durable_queue()
    {
        $durable = true;
        $doNotDelete = false;

        $configuration = ChannelConfiguration::durable();
        $configuration->configureQueue($this->channel, $this->exchangeName);

        $this->channel
            ->shouldHaveReceived('queue_declare')
            ->with($this->exchangeName, false, $durable, false, $doNotDelete)
        ;
    }

    /** @before */

    public function configureDoubles(): void
    {
        $this->channel = Mockery::spy(AMQPChannel::class);
    }

    /** @var AMQPChannel */
    private $channel;

    /** @var string */
    private $exchangeName = 'test_exchange';
}
