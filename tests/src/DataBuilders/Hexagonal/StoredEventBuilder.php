<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace DataBuilders\Hexagonal;

use DataBuilders\Ewallet\TransferWasMadeBuilder;
use Ewallet\Accounts\TransferWasMade;
use Faker\Factory;
use Hexagonal\Bridges\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\EventSerializer;
use Hexagonal\DomainEvents\StoredEvent;
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

    /**
     * @param integer $id
     * @return StoredEventBuilder
     */
    public function withId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return StoredEvent
     */
    public function build()
    {
        $event = new StoredEvent($this->body, $this->type, $this->occurredOn);
        $class = new ReflectionClass($event);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($event, $this->id);

        $this->reset();

        return $event;
    }

    protected function reset()
    {
        $this->id = $this->factory->numberBetween(1, 10000);
        $this->body = $this->serializer->serialize($this->eventBuilder->build());
        $this->type = TransferWasMade::class;
        $this->occurredOn = $this->factory->dateTimeThisMonth;
    }
}
