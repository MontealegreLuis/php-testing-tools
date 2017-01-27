<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\ContractTests\Messaging;

use Hexagonal\Messaging\MessageTracker;
use Hexagonal\Messaging\PublishedMessage;
use PHPUnit_Framework_TestCase as TestCase;

abstract class MessageTrackerTest extends TestCase
{
    const LAST_PUBLISHED_MESSAGE_ID = 5;

    /** @test */
    function it_recognize_empty_exchanges()
    {
        $tracker = $this->messageTracker();

        $this->assertFalse($tracker->hasPublishedMessages('empty_exchange'));
    }

    /** @test */
    function it_recognizes_non_empty_exchanges()
    {
        $tracker = $this->messageTracker();
        $message = new PublishedMessage('non_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);

        $this->assertTrue($tracker->hasPublishedMessages('non_empty_exchange'));
    }

    /**
     * @test
     * @expectedException \Hexagonal\Messaging\EmptyExchange
     */
    function it_throws_exception_when_trying_to_get_the_last_message_from_an_empty_exchange()
    {
        $tracker = $this->messageTracker();
        $tracker->mostRecentPublishedMessage('non_empty_exchange');
    }

    /** @test */
    function it_returns_last_published_message_in_a_given_exchange()
    {
        $tracker = $this->messageTracker();
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);
        $message->updateMostRecentMessageId($arbitraryId = 2);
        $tracker->track($message);
        $message->updateMostRecentMessageId($arbitraryId = 3);
        $tracker->track($message);
        $message->updateMostRecentMessageId(static::LAST_PUBLISHED_MESSAGE_ID);
        $tracker->track($message);

        $message = $tracker->mostRecentPublishedMessage('not_empty_exchange');

        $this->assertEquals(
            static::LAST_PUBLISHED_MESSAGE_ID,
            $message->mostRecentMessageId()
        );
    }

    /**
     * @test
     * @expectedException \Hexagonal\Messaging\InvalidPublishedMessageToTrack
     */
    function it_does_not_allow_more_than_one_last_message_for_each_exchange()
    {
        $tracker = $this->messageTracker();
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);
        // try to track a the same message
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);
    }

    abstract function messageTracker(): MessageTracker;
}
