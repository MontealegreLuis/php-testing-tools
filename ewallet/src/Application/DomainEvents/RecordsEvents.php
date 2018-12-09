<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

trait RecordsEvents
{
    /** @var Event[] */
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function recordThat(Event $event): void
    {
        $this->events[] = $event;
    }

    /** @return Event[] */
    public function events(): array
    {
        return $this->events;
    }
}
