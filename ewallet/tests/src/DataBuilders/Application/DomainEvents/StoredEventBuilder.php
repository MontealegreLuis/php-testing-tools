<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Application\DomainEvents;

use DateTime;
use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Application\DomainEvents\EventSerializer;
use Application\DomainEvents\StoredEvent;
use Application\Messaging\PublishedMessage;
use DataBuilders\Ewallet\Memberships\TransferWasMadeBuilder;
use Ewallet\Memberships\TransferWasMade;
use Faker\Factory;
use ReflectionClass;

class StoredEventBuilder
{
    /** @var Factory */
    private $factory;

    /** @var TransferWasMadeBuilder */
    private $eventBuilder;

    /** @var EventSerializer */
    private $serializer;

    /** @var integer */
    private $id;

    /** @var string */
    private $body;

    /** @var string */
    private $type;

    /** @var DateTime */
    private $occurredOn;

    /**
     * By default all the stored event bodies are taken from a `TransferWasMade`
     * event built with random values
     */
    public function __construct()
    {
        $this->eventBuilder = new TransferWasMadeBuilder();
        $this->factory = Factory::create();
        $this->serializer = new JsonSerializer();
        $this->reset();
    }

    public function withId(int $id): StoredEventBuilder
    {
        $this->id = $id;

        return $this;
    }

    public function withUnknownType(): StoredEventBuilder
    {
        $this->type = 'Ewallet\UnkownEvent';

        return $this;
    }

    public function from(PublishedMessage $message): StoredEventBuilder
    {
        return $this->withId($message->mostRecentMessageId());
    }

    public function build(): StoredEvent
    {
        $event = new StoredEvent($this->body, $this->type, $this->occurredOn);
        $class = new ReflectionClass($event);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($event, $this->id);

        $this->reset();

        return $event;
    }

    protected function reset(): void
    {
        $this->id = $this->factory->numberBetween(1, 10000);
        $this->body = $this->serializer->serialize($this->eventBuilder->build());
        $this->type = TransferWasMade::class;
        $this->occurredOn = $this->factory->dateTimeThisMonth;
    }
}
