<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Messaging;

/**
 * It will keep track of the last published message ID sent to the given exchange
 * There can only be either 0 or 1 entries associated to each exchange
 */
interface MessageTracker
{
    public function hasPublishedMessages(string $exchangeName): bool;

    /**
     * @throws EmptyExchange
     */
    public function mostRecentPublishedMessage(string $exchangeName): PublishedMessage;

    /**
     * It either creates or updates the only one entry associated to the message
     * exchange.
     *
     * If it's an update, it will only update the value for `mostRecentMessageId`
     *
     * @throws InvalidPublishedMessageToTrack This exception is thrown if there's
     * already a message but it is not equal to `mostRecentPublishedMessage`
     */
    public function track(PublishedMessage $mostRecentPublishedMessage): void;
}
