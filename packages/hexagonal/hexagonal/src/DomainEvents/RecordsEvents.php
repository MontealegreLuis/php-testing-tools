<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use SplObjectStorage;

trait RecordsEvents
{
    /** @var SplObjectStorage */
    private $events;

    /**
     * @param Event $event
     */
    public function recordThat(Event $event)
    {
        if (!$this->events) {
            $this->events = new SplObjectStorage();
        }

        $this->events->attach($event);
    }

    /**
     * @return SplObjectStorage
     */
    public function events()
    {
        return $this->events;
    }
}
