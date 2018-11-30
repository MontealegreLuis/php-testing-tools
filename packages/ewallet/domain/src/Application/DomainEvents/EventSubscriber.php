<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

interface EventSubscriber
{
    public function isSubscribedTo(Event $event): bool;

    public function handle(Event $event): void;
}
