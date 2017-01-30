<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Exception;
use Hexagonal\DomainEvents\EventStore;

class MessagePublisher
{
    private const NO_MESSAGES_PUBLISHED = 0;

    /** @var EventStore */
    private $store;

    /** @var MessageTracker */
    private $tracker;

    /** @var MessageProducer */
    private $producer;

    /** @var PublishedMessage */
    private $mostRecentMessage;

    /** @var \Hexagonal\DomainEvents\StoredEvent[] */
    private $unpublishedEvents;

    /** @var int */
    private $publishedMessagesCount;

    /** @var \Hexagonal\DomainEvents\StoredEvent */
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
     * @throws \Hexagonal\Messaging\InvalidPublishedMessageToTrack
     * @throws \Hexagonal\Messaging\EmptyExchange
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

        if (!$this->mostRecentMessage) {
            $this->createMostRecentMessage($exchangeName);
        } else {
            $this->updateMostRecentMessage();
        }

        $this->tracker->track($this->mostRecentMessage);

        return $this->publishedMessagesCount;
    }

    private function allEvents()
    {
        $this->mostRecentMessage = null;
        $this->unpublishedEvents = $this->store->allEvents();
    }

    /**
     * @param string $exchangeName
     * @throws \Hexagonal\Messaging\EmptyExchange
     */
    private function onlyUnpublishedEvents(string $exchangeName)
    {
        $this->mostRecentMessage = $this->tracker->mostRecentPublishedMessage(
            $exchangeName
        );
        $this->unpublishedEvents = $this->store->eventsStoredAfter(
            $this->mostRecentMessage->mostRecentMessageId()
        );
    }

    private function publish(string $exchangeName)
    {
        $this->producer->open($exchangeName);

        /** @var \Hexagonal\DomainEvents\StoredEvent $message */
        foreach ($this->unpublishedEvents as $message) {
            $this->producer->send($exchangeName, $message);
            $this->lastPublishedEvent = $message;
            $this->publishedMessagesCount++;
        }

        $this->producer->close();
    }

    private function createMostRecentMessage(string $exchangeName)
    {
        $this->mostRecentMessage = new PublishedMessage(
            $exchangeName,
            $this->lastPublishedEvent->id()
        );
    }

    private function updateMostRecentMessage()
    {
        $this->mostRecentMessage->updateMostRecentMessageId(
            $this->lastPublishedEvent->id()
        );
    }

    private function startMessageTracking()
    {
        $this->publishedMessagesCount = self::NO_MESSAGES_PUBLISHED;
        $this->lastPublishedEvent = null;
    }

    private function nothingToPublish(): bool
    {
        return empty($this->unpublishedEvents);
    }
}
