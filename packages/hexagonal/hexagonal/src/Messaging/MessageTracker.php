<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

interface MessageTracker
{
    public function hasPublishedMessages(string $exchangeName): bool;

    /**
     * @throws EmptyExchange
     */
    public function mostRecentPublishedMessage(
        string $exchangeName
    ): PublishedMessage;

    /**
     * @throws InvalidPublishedMessageToTrack
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);
}
