<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

/**
 * Events need to be serialized in order to persist them
 */
interface EventSerializer
{
    /**
     * @param Event $anEvent
     * @return string
     */
    public function serialize(Event $anEvent);
}
