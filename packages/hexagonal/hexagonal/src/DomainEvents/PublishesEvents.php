<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

trait PublishesEvents
{
    /** @var EventPublisher */
    private $publisher;

    /**
     * @param EventPublisher $publisher
     */
    public function setPublisher(EventPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function publisher()
    {
        if (!$this->publisher) {
            $this->publisher = new EventPublisher();
        }
        return $this->publisher;
    }
}
