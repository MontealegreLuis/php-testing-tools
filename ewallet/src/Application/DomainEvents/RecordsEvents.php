<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

trait RecordsEvents
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
