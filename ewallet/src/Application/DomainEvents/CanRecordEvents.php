<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Domain aggregates are usually the ones in charge to produce and record domain events
 */
interface CanRecordEvents
{
    public function recordThat(Event $event): void;

    /** @return Event[] */
    public function events(): array;
}
