<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

/**
 * It will keep track of the last published message ID sent to the given exchange
 * There can only be either 0 or 1 entry associated to each exchange
 */
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
     * It either creates or updates the only entry associated to the message
     * exchange.
     *
     * If it's an update, it will only update the value for `mostRecentMessageId`
     *
     * @throws InvalidPublishedMessageToTrack
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);
}
