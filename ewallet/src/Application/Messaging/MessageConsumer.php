<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Messaging;

use Closure;

interface MessageConsumer
{
    public function open(string $exchangeName): void;

    public function consume(string $exchangeName, Closure $callback): void;

    /**
     * Close channel and connection
     */
    public function close(): void;
}
