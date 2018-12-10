<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\DomainEvents;

use Application\DomainEvents\DomainEvent;
use DateTime;

class InstantaneousEvent implements DomainEvent
{
    /** @var DateTime */
    private $occurredOn;

    public function __construct(DateTime $instant)
    {
        $this->occurredOn = $instant;
    }

    /** @return DateTime */
    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
