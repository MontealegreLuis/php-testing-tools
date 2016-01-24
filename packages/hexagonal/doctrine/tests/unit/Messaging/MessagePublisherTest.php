<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

use Hexagonal\DataBuilders\A;
use Hexagonal\Fakes\Messaging\MessageProducerThatThrowsException;
use Hexagonal\DomainEvents\EventStore;
use Hexagonal\DomainEvents\StoredEvent;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class MessagePublisherTest extends TestCase
{
    /** @test */
    function it_should_publish_single_message_to_empty_exchange()
    {
        $emptyExchangeName = 'empty_exchange_name';
        $aSingleMessage = [
            $message = A::storedEvent()->build()
        ];

        $store = $this->givenAnEmptyStoreWith($aSingleMessage);
        $tracker = $this->givenAnEmptyExchange($emptyExchangeName);
        $this->expectToTrackFirstMessage($tracker);
        $producer = $this->expectToProcessSingle($message, $emptyExchangeName);

        $publisher = new MessagePublisher($store, $tracker, $producer);

        $messages = $publisher->publishTo($emptyExchangeName);

        $this->assertEquals(
            1,
            $messages,
            'Should have processed only 1 message'
        );
    }

    /** @test */
    function it_should_publish_several_messages_to_empty_exchange()
    {
        $emptyExchangeName = 'empty_exchange_name';
        $severalMessages = [
            A::storedEvent()->build(),
            A::storedEvent()->build(),
            A::storedEvent()->build(),
        ];

        $store = $this->givenAnEmptyStoreWith($severalMessages);
        $tracker = $this->givenAnEmptyExchange($emptyExchangeName);
        $this->expectToTrackFirstMessage($tracker);
        $producer = $this->expectToProcessAll($severalMessages, $emptyExchangeName);

        $publisher = new MessagePublisher($store, $tracker, $producer);

        $messages = $publisher->publishTo($emptyExchangeName);

        $this->assertEquals(
            3,
            $messages,
            'Should have processed 3 messages'
        );
    }

    /** @test */
    function it_should_publish_single_message_to_non_empty_exchange()
    {
        $nonEmptyExchangeName = 'non_empty_exchange_name';
        $aSingleMessage = [
            $eventMessage = A::storedEvent()->withId(11000)->build(),
        ];

        $store = $this->givenAStoreWith($aSingleMessage);
        $tracker = $this->givenANonEmptyExchange($nonEmptyExchangeName);
        $message = A::publishedMessage()
            ->withExchangeName($nonEmptyExchangeName)
            ->build()
        ;
        $this->givenMostRecentPublishedMessageIs($tracker, $message, $nonEmptyExchangeName);
        $this->expectToTrackUpdatedMessage($tracker, $message);
        $producer = $this->expectToProcessSingle($eventMessage, $nonEmptyExchangeName);

        $publisher = new MessagePublisher($store, $tracker, $producer);

        $messages = $publisher->publishTo($nonEmptyExchangeName);

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
    function it_should_publish_several_messages_to_non_empty_exchange()
    {
        $nonEmptyExchangeName = 'non_empty_exchange_name';
        $severalMessages = [
            A::storedEvent()->build(),
            A::storedEvent()->build(),
            $eventMessage = A::storedEvent()->withId(11000)->build(),
        ];

        $store = $this->givenAStoreWith($severalMessages);
        $tracker = $this->givenANonEmptyExchange($nonEmptyExchangeName);
        $message = A::publishedMessage()
            ->withExchangeName($nonEmptyExchangeName)
            ->build()
        ;
        $this->givenMostRecentPublishedMessageIs($tracker, $message, $nonEmptyExchangeName);
        $this->expectToTrackUpdatedMessage($tracker, $message);
        $producer = $this->expectToProcessAll($severalMessages, $nonEmptyExchangeName);

        $publisher = new MessagePublisher($store, $tracker, $producer);

        $messages = $publisher->publishTo($nonEmptyExchangeName);

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
    function it_should_update_last_published_message_when_publisher_fails_before_last_one()
    {
        $nonEmptyExchangeName = 'non_empty_exchange_name';
        $severalMessages = [
            A::storedEvent()->withId(12000)->build(),
            A::storedEvent()->withId(11000)->build(), //this will trigger the exception
            A::storedEvent()->build(),
        ];

        $store = $this->givenAStoreWith($severalMessages);
        $tracker = $this->givenANonEmptyExchange($nonEmptyExchangeName);
        $message = A::publishedMessage()
            ->withExchangeName($nonEmptyExchangeName)
            ->build()
        ;
        $this->givenMostRecentPublishedMessageIs($tracker, $message, $nonEmptyExchangeName);
        $this->expectToTrackUpdatedMessage($tracker, $message);
        $producer = new MessageProducerThatThrowsException();

        $publisher = new MessagePublisher($store, $tracker, $producer);

        $messages = $publisher->publishTo($nonEmptyExchangeName);

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

    /**
     * @param string $name
     * @return Mockery\MockInterface
     */
    protected function givenAnEmptyExchange($name)
    {
        $tracker = Mockery::mock(MessageTracker::class);
        $tracker
            ->shouldReceive('hasPublishedMessages')
            ->once()
            ->with($name)
            ->andReturn(false)
        ;

        return $tracker;
    }

    /**
     * @param string $name
     * @return Mockery\MockInterface
     */
    protected function givenANonEmptyExchange($name)
    {
        $tracker = Mockery::mock(MessageTracker::class);
        $tracker
            ->shouldReceive('hasPublishedMessages')
            ->once()
            ->with($name)
            ->andReturn(true)
        ;

        return $tracker;
    }

    /**
     * @param array $allEvents
     * @return Mockery\MockInterface
     */
    protected function givenAnEmptyStoreWith(array $allEvents)
    {
        $store = Mockery::mock(EventStore::class);
        $store
            ->shouldReceive('allEvents')
            ->once()
            ->andReturn($allEvents)
        ;

        return $store;
    }

    /**
     * @param array $events
     * @return Mockery\MockInterface
     */
    protected function givenAStoreWith(array $events)
    {
        $store = Mockery::mock(EventStore::class);
        $store
            ->shouldReceive('eventsStoredAfter')
            ->once()
            ->andReturn($events)
        ;

        return $store;
    }

    /**
     * @param StoredEvent[] $messages
     * @param string $emptyExchangeName
     * @return Mockery\MockInterface
     */
    protected function expectToProcessAll(array $messages, $emptyExchangeName)
    {
        $producer = Mockery::mock(MessageProducer::class);
        $producer
            ->shouldReceive('open')
            ->once()
            ->with($emptyExchangeName)
        ;
        $producer
            ->shouldReceive('send')
            ->times(count($messages))
            ->with($emptyExchangeName, Mockery::type(StoredEvent::class))
        ;
        $producer
            ->shouldReceive('close')
            ->once()
            ->with($emptyExchangeName)
        ;

        return $producer;
    }

    /**
     * @param StoredEvent $message
     * @param string $emptyExchangeName
     * @return Mockery\MockInterface
     */
    protected function expectToProcessSingle(StoredEvent $message, $emptyExchangeName)
    {
        $producer = Mockery::mock(MessageProducer::class);
        $producer
            ->shouldReceive('open')
            ->once()
            ->with($emptyExchangeName)
        ;
        $producer
            ->shouldReceive('send')
            ->once()
            ->with($emptyExchangeName, $message)
        ;
        $producer
            ->shouldReceive('close')
            ->once()
            ->with($emptyExchangeName)
        ;

        return $producer;
    }

    /**
     * @param Mockery\MockInterface $tracker
     */
    protected function expectToTrackFirstMessage($tracker)
    {
        $tracker
            ->shouldReceive('track')
            ->once()
            ->with(Mockery::type(PublishedMessage::class))
        ;
    }

    /**
     * @param Mockery\MockInterface $tracker
     * @param PublishedMessage
     */
    protected function expectToTrackUpdatedMessage(
        $tracker, PublishedMessage $message
    ) {
        $tracker
            ->shouldReceive('track')
            ->once()
            ->with($message)
        ;
    }

    /**
     * @param Mockery\MockInterface $tracker
     * @param PublishedMessage $message
     * @param string $exchangeName
     */
    protected function givenMostRecentPublishedMessageIs(
        $tracker,
        PublishedMessage $message,
        $exchangeName
    ) {
        $tracker
            ->shouldReceive('mostRecentPublishedMessage')
            ->once()
            ->with($exchangeName)
            ->andReturn($message)
        ;
    }
}
