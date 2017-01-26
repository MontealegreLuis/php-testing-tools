<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Exception;
use Hexagonal\DataBuilders\A;
use Hexagonal\DomainEvents\{EventStore, StoredEvent};
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;

class MessagePublisherTest extends TestCase
{
    /** @test */
    function it_publishes_a_single_message_to_an_empty_exchange()
    {
        $exchangeName = 'exchange_name';
        $aSingleMessage = [$message = A::storedEvent()->build()];
        $this->store->allEvents()->willReturn($aSingleMessage);
        $this->tracker->hasPublishedMessages($exchangeName)->willReturn(false);
        $this->tracker->track(Argument::type(PublishedMessage::class))->shouldBeCalled();

        $messages = $this->publisher->publishTo($exchangeName);

        $this->producer->send($exchangeName, $message)->shouldHaveBeenCalled();
        $this->assertEquals(
            1,
            $messages,
            'Should have processed only 1 message'
        );
    }

    /** @test */
    function it_publishes_several_messages_to_an_empty_exchange()
    {
        $exchangeName = 'exchange_name';
        $severalMessages = [
            A::storedEvent()->build(),
            A::storedEvent()->build(),
            A::storedEvent()->build(),
        ];
        $messagesCount = count($severalMessages);
        $this->store->allEvents()->willReturn($severalMessages);
        $this->tracker->hasPublishedMessages($exchangeName)->willReturn(false);
        $this->tracker->track(Argument::type(PublishedMessage::class))->shouldBeCalled();

        $messages = $this->publisher->publishTo($exchangeName);

        $this
            ->producer
            ->send($exchangeName, Argument::type(StoredEvent::class))
            ->shouldHaveBeenCalledTimes($messagesCount)
        ;
        $this->assertEquals(
            $messagesCount,
            $messages,
            'Should have processed 3 messages'
        );
    }

    /** @test */
    function it_publishes_a_single_message_to_a_non_empty_exchange()
    {
        $exchangeName = 'exchange_name';
        $aSingleMessage = [
            $eventMessage = A::storedEvent()->withId(11000)->build(),
        ];
        $this->tracker->hasPublishedMessages($exchangeName)->willReturn(true);
        $message = A::publishedMessage()
            ->withExchangeName($exchangeName)
            ->build()
        ;
        $this
            ->store
            ->eventsStoredAfter($message->mostRecentMessageId())
            ->willReturn($aSingleMessage)
        ;
        $this->tracker->mostRecentPublishedMessage($exchangeName)->willReturn($message);
        $this->tracker->track($message)->shouldBeCalled();

        $messages = $this->publisher->publishTo($exchangeName);

        $this->producer->send($exchangeName, $eventMessage)->shouldHaveBeenCalled();
        $this->assertEquals(
            1,
            $messages,
            'Should have processed only 1 message'
        );
        $this->assertEquals(
            11000,
            $message->mostRecentMessageId(),
            'Most recent message ID should be 11000'
        );
    }

    /** @test */
    function it_publishes_several_messages_to_a_non_empty_exchange()
    {
        $exchangeName = 'exchange_name';
        $severalMessages = [
            A::storedEvent()->build(),
            A::storedEvent()->build(),
            $eventMessage = A::storedEvent()->withId(11000)->build(),
        ];
        $messagesCount = count($severalMessages);
        $message = A::publishedMessage()
            ->withExchangeName($exchangeName)
            ->build()
        ;
        $this
            ->store
            ->eventsStoredAfter($message->mostRecentMessageId())
            ->willReturn($severalMessages)
        ;
        $this->tracker->hasPublishedMessages($exchangeName)->willReturn(true);
        $this
            ->tracker
            ->mostRecentPublishedMessage($exchangeName)
            ->willReturn($message)
        ;
        $this->tracker->track($message)->shouldBeCalled();

        $messages = $this->publisher->publishTo($exchangeName);

        $this
            ->producer
            ->send($exchangeName, Argument::type(StoredEvent::class))
            ->shouldHaveBeenCalledTimes($messagesCount)
        ;
        $this->assertEquals(
            3,
            $messages,
            'Should have processed 3 messages'
        );
        $this->assertEquals(
            11000,
            $message->mostRecentMessageId(),
            'Most recent message ID should be 11000'
        );
    }

    /** @test */
    function it_updates_last_published_message_when_publisher_fails_before_last_one()
    {
        $exchangeName = 'exchange_name';
        $eventCausingException = A::storedEvent()->withId(11000)->build();
        $eventToBeProcessed = A::storedEvent()->withId(12000)->build();
        $severalMessages = [
            $eventToBeProcessed,
            $eventCausingException,
            A::storedEvent()->build(),
        ];
        $message = A::publishedMessage()
            ->withExchangeName($exchangeName)
            ->build()
        ;
        $this
            ->store
            ->eventsStoredAfter($message->mostRecentMessageId())
            ->willReturn($severalMessages)
        ;
        $this->tracker->hasPublishedMessages($exchangeName)->willReturn(true);
        $this
            ->tracker
            ->mostRecentPublishedMessage($exchangeName)
            ->willReturn($message)
        ;
        $this->tracker->track($message)->shouldBeCalled();
        $this->producer->open($exchangeName)->shouldBeCalled();
        $this
            ->producer
            ->send($exchangeName, $eventToBeProcessed)
            ->shouldBeCalled()
        ;
        $this
            ->producer
            ->send($exchangeName, $eventCausingException)
            ->willThrow(Exception::class)
        ;

        $messages = $this->publisher->publishTo($exchangeName);

        $this->assertEquals(
            1,
            $messages,
            'Should have processed only 1 message'
        );
        $this->assertEquals(
            12000,
            $message->mostRecentMessageId(),
            'Most recent message ID should be 12000'
        );
    }

    /** @before */
    function configurePublisher()
    {
        $this->store = $this->prophesize(EventStore::class);
        $this->tracker = $this->prophesize(MessageTracker::class);
        $this->producer = $this->prophesize(MessageProducer::class);
        $this->publisher = new MessagePublisher(
            $this->store->reveal(),
            $this->tracker->reveal(),
            $this->producer->reveal()
        );
    }

    /** @var MessagePublisher */
    private $publisher;

    /** @var MessageProducer */
    private $producer;

    /** @var MessageTracker */
    private $tracker;

    /** @var EventStore */
    private $store;
}
