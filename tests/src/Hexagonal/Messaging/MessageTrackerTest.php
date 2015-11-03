<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use PHPUnit_Framework_TestCase as TestCase;

abstract class MessageTrackerTest extends TestCase
{
    const LAST_PUBLISHED_MESSAGE_ID = 5;

    /** @test */
    function it_should_recognize_empty_exchanges()
    {
        $tracker = $this->messageTracker();

        $this->assertFalse($tracker->hasPublishedMessages('empty_exchange'));
    }

    /** @test */
    function it_should_recognize_non_empty_exchanges()
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
    function it_should_throw_exception_when_trying_to_get_the_last_message_from_an_empty_exchange()
    {
        $tracker = $this->messageTracker();
        $tracker->mostRecentPublishedMessage('non_empty_exchange');
    }

    /** @test */
    function it_should_return_last_published_message_in_a_given_exchange()
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
    function it_should_not_allow_more_than_one_last_message_for_each_exchange()
    {
        $tracker = $this->messageTracker();
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);
        // try to track a different message
        $message = new PublishedMessage('not_empty_exchange', $arbitraryId = 1);
        $tracker->track($message);
    }

    /**
     * @return MessageTracker
     */
    abstract function messageTracker();
}
