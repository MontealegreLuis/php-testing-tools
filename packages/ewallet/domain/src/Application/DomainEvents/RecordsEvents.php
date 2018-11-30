<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use SplObjectStorage;
use Traversable;

trait RecordsEvents
{
    /** @var SplObjectStorage */
    private $events;

    public function recordThat(Event $event): void
    {
        if (!$this->events) {
            $this->events = new SplObjectStorage();
        }

        $this->events->attach($event);
    }

    public function events(): Traversable
    {
        return $this->events;
    }
}
