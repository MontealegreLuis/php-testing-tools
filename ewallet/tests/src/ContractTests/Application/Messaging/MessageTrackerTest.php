<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ContractTests\Application\Messaging;

use Application\Messaging\EmptyExchange;
use Application\Messaging\InvalidPublishedMessageToTrack;
use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;
use DataBuilders\A;
use PHPUnit\Framework\TestCase;

abstract class MessageTrackerTest extends TestCase
{
    /** @test */
    function it_knows_there_is_no_published_messages_to_track()
    {
        $this->assertFalse($this->tracker->hasPublishedMessages('empty_exchange'));
    }

    /** @test */
    function it_knows_there_is_published_messages_to_track()
    {
        $message = new PublishedMessage('non_empty_exchange', $arbitraryId = 1);
        $this->tracker->track($message);

        $this->assertTrue($this->tracker->hasPublishedMessages('non_empty_exchange'));
    }

    /** @test */
    function it_cannot_track_messages_from_empty_exchanges()
    {
        $this->expectException(EmptyExchange::class);
        $this->tracker->mostRecentPublishedMessage('non_empty_exchange');
    }

    /** @test */
    function it_returns_last_published_message_in_a_given_exchange()
    {
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $this->tracker->track($message);
        $message->updateMostRecentMessageId($arbitraryId = 2);
        $this->tracker->track($message);
        $message->updateMostRecentMessageId($arbitraryId = 3);
        $this->tracker->track($message);
        $message->updateMostRecentMessageId(static::LAST_PUBLISHED_MESSAGE_ID);
        $this->tracker->track($message);

        $message = $this->tracker->mostRecentPublishedMessage('not_empty_exchange');

        $this->assertEquals(
            static::LAST_PUBLISHED_MESSAGE_ID,
            $message->mostRecentMessageId()
        );
    }

    /** @test */
    function it_cannot_track_a_published_message_unles_it_is_the_most_recent_in_a_given_exchange()
    {
        $originalId = 1;
        $aDifferentId = 2;
        $exchangeName = 'non_empty_exchange';
        $this->tracker->track(
            A::publishedMessage()
                ->withExchangeName($exchangeName)
                ->withId($originalId)
                ->build()
        );
        $aDifferentMessage = A::publishedMessage()
            ->withExchangeName($exchangeName)
            ->withId($aDifferentId)
            ->build();

        $this->expectException(InvalidPublishedMessageToTrack::class);
        $this->tracker->track($aDifferentMessage);
    }

    /** @before */
    function let()
    {
        $this->tracker = $this->messageTracker();
    }

    abstract function messageTracker(): MessageTracker;

    protected MessageTracker $tracker;
    protected const LAST_PUBLISHED_MESSAGE_ID = 5;
}
