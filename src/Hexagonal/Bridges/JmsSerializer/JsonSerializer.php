<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\JmsSerializer;

use DateTime;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSerializer;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use Money\Currency;

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
                    Currency::class,
                    'json',
                    function ($visitor, Currency $currency, array $type) {
                        return $currency->getName();
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
