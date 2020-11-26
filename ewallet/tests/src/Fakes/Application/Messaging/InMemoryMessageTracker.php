<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application\Messaging;

use Application\Messaging\EmptyExchange;
use Application\Messaging\InvalidPublishedMessageToTrack;
use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;

class InMemoryMessageTracker implements MessageTracker
{
    /** @var PublishedMessage[] */
    private array $messages = [];

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
        if (! $this->hasPublishedMessages($exchangeName)) {
            throw new EmptyExchange("No published messages in exchange $exchangeName");
        }
        return array_pop($this->messages);
    }

    /**
     * @throws InvalidPublishedMessageToTrack
     */
    public function track(PublishedMessage $mostRecentPublishedMessage): void
    {
        if (count($this->messages) > 0
            && ! $this->messages[count($this->messages) - 1]->equals($mostRecentPublishedMessage)
        ) {
            throw new InvalidPublishedMessageToTrack();
        }
        $this->messages[] = $mostRecentPublishedMessage;
    }
}
