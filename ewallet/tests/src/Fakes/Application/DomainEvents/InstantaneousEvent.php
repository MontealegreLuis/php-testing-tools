<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application\DomainEvents;

use Application\DomainEvents\DomainEvent;
use DateTimeInterface;

class InstantaneousEvent implements DomainEvent
{
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(DateTimeInterface $instant)
    {
        $this->occurredOn = $instant;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
