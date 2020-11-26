<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\JmsSerializer\Application\DomainEvents;

use Application\DomainEvents\DomainEvent;
use Application\DomainEvents\EventSerializer;
use Carbon\CarbonImmutable;
use Ewallet\Memberships\MemberId;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Money\Money;

class JsonSerializer implements EventSerializer
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry): void {
                // We only need to serialize the currency name
                $registry->registerHandler(
                    GraphNavigator::DIRECTION_SERIALIZATION,
                    Money::class,
                    'json',
                    fn($visitor, Money $money, array $type): string => $money->getAmount()
                );
                // We only need the value of the ID
                $registry->registerHandler(
                    GraphNavigator::DIRECTION_SERIALIZATION,
                    MemberId::class,
                    'json',
                    fn($visitor, MemberId $id, array $type): string => (string) $id
                );
                // Use specific format for date/time objects
                $registry->registerHandler(
                    GraphNavigator::DIRECTION_SERIALIZATION,
                    CarbonImmutable::class,
                    'json',
                    fn($visitor, CarbonImmutable $dateTime, array $type): string => $dateTime->format('Y-m-d H:i:s')
                );
            })
            ->build();
    }

    public function serialize(DomainEvent $anEvent): string
    {
        return $this->serializer->serialize($anEvent, 'json');
    }
}
