<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use DateTime;

/**
 * An event is something relevant to the domain that happened in the past
 */
interface DomainEvent
{
    public function occurredOn(): DateTime;
}
