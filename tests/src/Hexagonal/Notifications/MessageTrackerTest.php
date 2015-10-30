<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Notifications;

use PHPUnit_Framework_TestCase as TestCase;

abstract class MessageTrackerTest extends TestCase
{
    const LAST_PUBLISHED_MESSAGE_ID = 5;

    /** @test */
    function it_should_return_null_if_no_message_has_been_published_to_a_given_exchange()
    {
        $tracker = $this->messageTracker();

        $this->assertNull($tracker->mostRecentPublishedMessage('empty_exchange'));
    }

    /** @test */
    function it_should_return_last_message_for_a_given_exchange()
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
     * @return MessageTracker
     */
    abstract function messageTracker();
}
