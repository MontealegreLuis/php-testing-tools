<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Exception;
use Hexagonal\DomainEvents\StoredEvent;

class MessageProducerThatThrowsException implements MessageProducer
{
    /**
     * @param string $exchangeName
     */
    public function open($exchangeName)
    {
    }

    /**
     * @param string $exchangeName
     * @param StoredEvent $notification
     * @throws Exception
     */
    public function send($exchangeName, StoredEvent $notification)
    {
        if (11000 === $notification->id()) {
            throw new Exception();
        }
    }

    /**
     * @param string $exchangeName
     */
    public function close($exchangeName)
    {
    }
}
