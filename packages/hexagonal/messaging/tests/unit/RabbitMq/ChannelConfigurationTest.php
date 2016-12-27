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
        $channel = Mockery::spy(AMQPChannel::class);
        $nonDurable = false;
        $autoDelete = true;
        $configuration = new ChannelConfiguration();

        $configuration->temporary();
        $configuration->configureExchange($channel, 'test_exchange');

        $channel
            ->shouldHaveReceived('exchange_declare')
            ->with('test_exchange', 'fanout', false, $nonDurable, $autoDelete)
        ;
    }

    /** @test */
    function it_configures_a_durable_exchange()
    {
        $channel = Mockery::spy(AMQPChannel::class);
        $durable = true;
        $doNotDelete = false;
        $configuration = new ChannelConfiguration();

        $configuration->configureExchange($channel, 'test_exchange');

        $channel
            ->shouldHaveReceived('exchange_declare')
            ->with('test_exchange', 'fanout', false, $durable, $doNotDelete)
        ;
    }

    /** @test */
    function it_configures_a_temporary_queue()
    {
        $channel = Mockery::spy(AMQPChannel::class);
        $nonDurable = false;
        $autoDelete = true;
        $configuration = new ChannelConfiguration();

        $configuration->temporary();
        $configuration->configureQueue($channel, 'test_exchange');

        $channel
            ->shouldHaveReceived('queue_declare')
            ->with('test_exchange', false, $nonDurable, false, $autoDelete)
        ;
    }

    /** @test */
    function it_configures_a_durable_queue()
    {
        $channel = Mockery::spy(AMQPChannel::class);
        $durable = true;
        $doNotDelete = false;
        $configuration = new ChannelConfiguration();

        $configuration->configureQueue($channel, 'test_exchange');

        $channel
            ->shouldHaveReceived('queue_declare')
            ->with('test_exchange', false, $durable, false, $doNotDelete)
        ;
    }
}
