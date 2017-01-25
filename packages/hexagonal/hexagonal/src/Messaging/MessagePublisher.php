<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Exception;
use Hexagonal\DomainEvents\EventStore;

class MessagePublisher
{
    /** @var EventStore */
    private $store;

    /** @var  MessageTracker */
    private $tracker;

    /** @var MessageProducer */
    private $producer;

    /**
     * @param EventStore $store
     * @param MessageTracker $tracker
     * @param MessageProducer $producer
     */
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
     * @param string $exchangeName
     * @return int
     */
    public function publishTo(string $exchangeName): int
    {
        if (!$this->tracker->hasPublishedMessages($exchangeName)) {
            $mostRecentMessage = null;
            $messages = $this->store->allEvents();
        } else {
            $mostRecentMessage = $this->tracker->mostRecentPublishedMessage($exchangeName);
            $messages = $this->store->eventsStoredAfter($mostRecentMessage->mostRecentMessageId());
        }

        if (!$messages) {
            return 0;
        }

        $publishedMessages = 0;
        $lastPublishedNotification = null;

        try {
            $this->producer->open($exchangeName);
            foreach ($messages as $message) {
                $this->producer->send($exchangeName, $message);
                $lastPublishedNotification = $message;
                $publishedMessages++;
            }
            $this->producer->close();
        } catch (Exception $e) {
        } finally {
            if (!$mostRecentMessage) {
                $mostRecentMessage = new PublishedMessage(
                    $exchangeName,
                    $lastPublishedNotification->id()
                );
            } else {
                $mostRecentMessage->updateMostRecentMessageId(
                    $lastPublishedNotification->id()
                );
            }
            $this->tracker->track($mostRecentMessage);
        }

        return $publishedMessages;
    }
}
