<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

interface MessageConsumer
{
    /**
     * @param string $exchangeName
     */
    public function open($exchangeName);

    /**
     * @param string $exchangeName
     * @param callable $callback
     */
    public function consume($exchangeName, callable $callback);

    /**
     * Close channel and connection
     */
    public function close();
}
