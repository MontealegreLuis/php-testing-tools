<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

interface MessageTracker
{
    /**
     * @param string $exchangeName
     * @return bool
     */
    public function hasPublishedMessages(string $exchangeName): bool;

    /**
     * @param string $exchangeName
     * @return PublishedMessage
     * @throws EmptyExchange
     */
    public function mostRecentPublishedMessage(
        string $exchangeName
    ): PublishedMessage;

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     * @throws InvalidPublishedMessageToTrack
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);
}
