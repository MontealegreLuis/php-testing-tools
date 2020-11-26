<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Domain aggregates are usually the ones in charge to produce and record domain events
 */
interface CanRecordEvents
{
    public function recordThat(DomainEvent $event): void;

    /** @return DomainEvent[] */
    public function events(): array;
}
