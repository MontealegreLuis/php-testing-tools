<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use Traversable;

interface CanRecordEvents
{
    /**
     * @param Event $event
     */
    public function recordThat(Event $event);

    /**
     * @return Traversable
     */
    public function events(): Traversable;
}
