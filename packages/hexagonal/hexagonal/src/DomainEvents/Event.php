<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;

interface Event
{
    /**
     * @return DateTime
     */
    public function occurredOn(): DateTime;
}
