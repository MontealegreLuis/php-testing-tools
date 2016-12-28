<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;

class ChannelConfiguration
{
    /** @var bool */
    private $durable;

    /** @var bool */
    private $autoDeletes;

    private function __construct(bool $isDurable, bool $autoDelete)
    {
        $this->durable = $isDurable;
        $this->autoDeletes = $autoDelete;
    }

    /**
     * Make the exchange and/or the queue temporary
     */
    public static function temporary(): ChannelConfiguration
    {
        return new ChannelConfiguration(false, true);
    }

    public static function durable(): ChannelConfiguration
    {
        return new ChannelConfiguration(true, false);
    }

    public function configureExchange(AMQPChannel $channel, string $exchangeName)
    {
        $channel->exchange_declare(
            $exchangeName, 'fanout', false, $this->durable, $this->autoDeletes
        );
    }

    public function configureQueue(AMQPChannel $channel, string $exchangeName)
    {
        $channel->queue_declare(
            $exchangeName, false, $this->durable, false, $this->autoDeletes
        );
    }
}
