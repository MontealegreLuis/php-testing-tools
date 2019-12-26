<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Events need to be serialized in order to persist them
 */
interface EventSerializer
{
    public function serialize(DomainEvent $anEvent): string;
}
