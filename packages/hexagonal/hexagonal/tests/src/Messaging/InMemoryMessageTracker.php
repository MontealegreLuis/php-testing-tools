<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

class InMemoryMessageTracker implements MessageTracker
{
    /** @var PublishedMessage[] */
    private $messages = [];

    public function hasPublishedMessages(string $exchangeName): bool
    {
        return count($this->messages) > 0;
    }

    /**
     * @throws EmptyExchange
     */
    public function mostRecentPublishedMessage(
        string $exchangeName
    ): PublishedMessage {
        if (!$this->hasPublishedMessages($exchangeName)) {
            throw new EmptyExchange("No published messages in exchange $exchangeName");
        }
        return array_pop($this->messages);
    }

    /**
     * @throws InvalidPublishedMessageToTrack
     */
    public function track(PublishedMessage $mostRecentPublishedMessage)
    {
        if (count($this->messages) > 0
            && !$this->messages[count($this->messages) - 1]->equals($mostRecentPublishedMessage)
        ) {
            throw new InvalidPublishedMessageToTrack();
        }
        $this->messages[] = $mostRecentPublishedMessage;
    }
}
