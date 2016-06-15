<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Hexagonal\DomainEvents\StoredEvent;

interface MessageProducer
{
    /**
     * @param string $exchangeName
     */
    public function open(string $exchangeName);

    /**
     * @param string $exchangeName
     * @param StoredEvent $notification
     */
    public function send(string $exchangeName, StoredEvent $notification);

    /**
     * Close channel and connection
     */
    public function close();
}
