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
    /**
     * @return void
     */
    public function open(string $exchangeName);

    /**
     * @return void
     */
    public function send(string $exchangeName, StoredEvent $notification);

    /**
     * Close channel and connection
     *
     * @return void
     */
    public function close();
}
