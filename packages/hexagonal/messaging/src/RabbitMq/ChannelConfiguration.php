<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;

class ChannelConfiguration
{
    /** @var bool */
    private $durable = true;

    /** @var bool */
    private $autoDeletes = false ;

    /**
     * Make the exchange and/or the queue temporary
     */
    public function temporary()
    {
        $this->durable = false;
        $this->autoDeletes = true;
    }

    /**
     * @param AMQPChannel $channel
     * @param string $exchangeName
     */
    public function configureExchange(AMQPChannel $channel, string $exchangeName)
    {
        $channel->exchange_declare(
            $exchangeName, 'fanout', false, $this->durable, $this->autoDeletes
        );
    }

    /**
     * @param AMQPChannel $channel
     * @param string $exchangeName
     */
    public function configureQueue(AMQPChannel $channel, string $exchangeName)
    {
        $channel->queue_declare(
            $exchangeName, false, $this->durable, false, $this->autoDeletes
        );
    }
}
