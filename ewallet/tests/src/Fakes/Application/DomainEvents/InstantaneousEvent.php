<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application\DomainEvents;

use Application\Clock;
use Application\DomainEvents\DomainEvent;
use DateTimeInterface;

class InstantaneousEvent implements DomainEvent
{
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(Clock $clock)
    {
        $this->occurredOn = $clock->now();
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
