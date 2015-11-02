<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Notifications;

use Hexagonal\DomainEvents\StoredEvent;

interface MessageProducer
{
    /**
     * @param string $exchangeName
     */
    public function open($exchangeName);

    /**
     * @param string $exchangeName
     * @param StoredEvent $notification
     */
    public function send($exchangeName, StoredEvent $notification);

    /**
     * @param string $exchangeName
     */
    public function close($exchangeName);
}
