<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\JmsSerializer;

use DateTime;
use Ewallet\Accounts\Identifier;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSerializer;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use Money\Money;

class JsonSerializer implements EventSerializer
{
    /** @var \JMS\Serializer\Serializer */
    private $serializer;

    /**
     * JsonSerializer constructor.
     */
    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry) {
                // We only need to serialize the currency name
                $registry->registerHandler(
                    'serialization',
                    Money::class,
                    'json',
                    function ($visitor, Money $money, array $type) {
                        return $money->getAmount();
                    }
                );
                // We only need the value of the ID
                $registry->registerHandler(
                    'serialization',
                    Identifier::class,
                    'json',
                    function ($visitor, Identifier $id, array $type) {
                        return (string) $id;
                    }
                );
                // Use specific format for date/time objects
                $registry->registerHandler(
                    'serialization',
                    DateTime::class,
                    'json',
                    function ($visitor, DateTime $dateTime, array $type) {
                        return $dateTime->format('Y-m-d H:i:s');
                    }
                );
            })
            ->build()
        ;
    }

    /**
     * @param Event $anEvent
     * @return string
     */
    public function serialize(Event $anEvent)
    {
        return $this->serializer->serialize($anEvent, 'json');
    }
}
