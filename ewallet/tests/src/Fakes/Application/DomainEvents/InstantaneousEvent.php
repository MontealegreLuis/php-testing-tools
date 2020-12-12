<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application\DomainEvents;

use Application\Clock;
use Application\DomainEvents\DomainEvent;
use DateTimeInterface;

final class InstantaneousEvent implements DomainEvent
{
    private DateTimeInterface $occurredOn;

    public function __construct(Clock $clock)
    {
        $this->occurredOn = $clock->now();
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
