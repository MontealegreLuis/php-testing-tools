<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Messaging;

use Application\DomainEvents\EventStore;
use Exception;

class MessagePublisher
{
    private const NO_MESSAGES_PUBLISHED = 0;

    /** @var EventStore */
    private $store;

    /** @var MessageTracker */
    private $tracker;

    /** @var MessageProducer */
    private $producer;

    /** @var PublishedMessage|null */
    private $mostRecentMessage;

    /** @var \Application\DomainEvents\StoredEvent[] */
    private $unpublishedEvents;

    /** @var int */
    private $publishedMessagesCount;

    /** @var \Application\DomainEvents\StoredEvent|null */
    private $lastPublishedEvent;

    public function __construct(
        EventStore $store,
        MessageTracker $tracker,
        MessageProducer $producer
    ) {
        $this->store = $store;
        $this->tracker = $tracker;
        $this->producer = $producer;
    }

    /**
     * @return int The amount of messages that were published
     * @throws \Application\Messaging\InvalidPublishedMessageToTrack
     * @throws \Application\Messaging\EmptyExchange
     */
    public function publishTo(string $exchangeName): int
    {
        $this->startMessageTracking();

        if ($this->tracker->hasPublishedMessages($exchangeName)) {
            $this->onlyUnpublishedEvents($exchangeName);
        } else {
            $this->allEvents();
        }

        if ($this->nothingToPublish()) {
            return self::NO_MESSAGES_PUBLISHED;
        }

        try {
            $this->publish($exchangeName);
        } catch (Exception $ignore) {
            /* Ignore any exception produced by any consumer */
        }

        if ($this->lastPublishedEvent === null) {
            return 0; // All unpublished events failed to be published
        }

        if ($this->mostRecentMessage === null) {
            $this->mostRecentMessage = new PublishedMessage($exchangeName, $this->lastPublishedEvent->id());
        } else {
            $this->mostRecentMessage->updateMostRecentMessageId($this->lastPublishedEvent->id());
        }

        $this->tracker->track($this->mostRecentMessage);

        return $this->publishedMessagesCount;
    }

    private function allEvents(): void
    {
        $this->mostRecentMessage = null;
        $this->unpublishedEvents = $this->store->allEvents();
    }

    /**
     * @throws \Application\Messaging\EmptyExchange
     */
    private function onlyUnpublishedEvents(string $exchangeName): void
    {
        $this->mostRecentMessage = $this->tracker->mostRecentPublishedMessage(
            $exchangeName
        );
        $this->unpublishedEvents = $this->store->eventsStoredAfter(
            $this->mostRecentMessage->mostRecentMessageId()
        );
    }

    private function publish(string $exchangeName): void
    {
        $this->producer->open($exchangeName);

        /** @var \Application\DomainEvents\StoredEvent $message */
        foreach ($this->unpublishedEvents as $message) {
            $this->producer->send($exchangeName, $message);
            $this->lastPublishedEvent = $message;
            $this->publishedMessagesCount++;
        }

        $this->producer->close();
    }

    private function startMessageTracking(): void
    {
        $this->publishedMessagesCount = self::NO_MESSAGES_PUBLISHED;
        $this->lastPublishedEvent = null;
    }

    private function nothingToPublish(): bool
    {
        return count($this->unpublishedEvents) === 0;
    }
}
