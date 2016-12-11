<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

interface EventSubscriber
{
    /**
     * @param Event $event
     * @return bool
     */
    public function isSubscribedTo(Event $event): bool;

    /**
     * @param Event $event
     */
    public function handle(Event $event): void;
}
