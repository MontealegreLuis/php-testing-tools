<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use Traversable;

interface CanRecordEvents
{
    public function recordThat(Event $event): void;

    public function events(): Traversable;
}
