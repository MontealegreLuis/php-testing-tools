<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Fakes\Messaging;

use Exception;
use Hexagonal\{DomainEvents\StoredEvent, Messaging\MessageProducer};

class MessageProducerThatThrowsException implements MessageProducer
{
    /**
     * @param string $exchangeName
     */
    public function open(string $exchangeName)
    {
    }

    /**
     * @param string $exchangeName
     * @param StoredEvent $notification
     * @throws Exception
     */
    public function send(string $exchangeName, StoredEvent $notification)
    {
        if (11000 === $notification->id()) {
            throw new Exception();
        }
    }

    /**
     * Close channel and connection
     */
    public function close()
    {
    }
}
