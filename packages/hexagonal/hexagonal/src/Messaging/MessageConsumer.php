<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Closure;

interface MessageConsumer
{
    /**
     * @return void
     */
    public function open(string $exchangeName);

    /**
     * @return void
     */
    public function consume(string $exchangeName, Closure $callback);

    /**
     * Close channel and connection
     *
     * @return void
     */
    public function close();
}
