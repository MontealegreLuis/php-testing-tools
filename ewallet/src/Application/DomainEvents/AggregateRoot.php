<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Domain aggregates are usually the ones in charge to produce and record domain events
 */
abstract class AggregateRoot
{
    /** @var DomainEvent[] */
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function recordThat(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /** @return DomainEvent[] */
    public function events(): array
    {
        return $this->events;
    }
}
