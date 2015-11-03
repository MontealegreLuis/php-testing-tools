<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

interface MessageTracker
{
    /**
     * @param string $exchangeName
     * @return boolean
     */
    public function hasPublishedMessages($exchangeName);

    /**
     * @param string $exchangeName
     * @return PublishedMessage
     * @throws EmptyExchange
     */
    public function mostRecentPublishedMessage($exchangeName);

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);
}
