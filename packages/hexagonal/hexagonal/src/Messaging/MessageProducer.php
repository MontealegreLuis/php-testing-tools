<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Hexagonal\DomainEvents\StoredEvent;

interface MessageProducer
{
    public function open(string $exchangeName): void;

    public function send(string $exchangeName, StoredEvent $notification): void;

    /**
     * Close channel and connection
     */
    public function close(): void;
}
