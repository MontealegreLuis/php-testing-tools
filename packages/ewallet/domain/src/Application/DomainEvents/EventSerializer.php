<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

/**
 * Events need to be serialized in order to persist them
 */
interface EventSerializer
{
    public function serialize(Event $anEvent): string;
}
