<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

interface CanRecordEvents
{
    /**
     * @param Event $event
     */
    public function recordThat(Event $event);

    /**
     * @return \Traversable
     */
    public function events();
}
