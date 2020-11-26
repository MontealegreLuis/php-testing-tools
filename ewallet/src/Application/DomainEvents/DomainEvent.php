<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use DateTimeInterface;

/**
 * An event is something relevant to the domain that happened in the past
 */
interface DomainEvent
{
    public function occurredOn(): DateTimeInterface;
}
