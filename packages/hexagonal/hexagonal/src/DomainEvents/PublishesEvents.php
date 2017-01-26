<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

trait PublishesEvents
{
    /** @var EventPublisher */
    private $publisher;

    public function setPublisher(EventPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publisher(): EventPublisher
    {
        if (!$this->publisher) {
            $this->publisher = new EventPublisher();
        }
        return $this->publisher;
    }
}
