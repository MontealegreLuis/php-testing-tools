<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ports\JmsSerializer\DomainEvents;

use DateTime;
use Ewallet\Memberships\MemberId;
use Hexagonal\DomainEvents\{Event, EventSerializer};
use JMS\Serializer\{Handler\HandlerRegistry, SerializerBuilder};
use Money\Money;

class JsonSerializer implements EventSerializer
{
    /** @var \JMS\Serializer\Serializer */
    private $serializer;

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
                    MemberId::class,
                    'json',
                    function ($visitor, MemberId $id, array $type) {
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

    public function serialize(Event $anEvent): string
    {
        return $this->serializer->serialize($anEvent, 'json');
    }
}
