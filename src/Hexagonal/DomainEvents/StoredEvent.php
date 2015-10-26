<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DomainEvents;

use DateTime;

/**
 * Representation of a persisted domain event
 */
class StoredEvent implements Event
{
    /** @var integer */
    private $id;

    /** @var string */
    private $body;

    /** @var string */
    private $type;

    /** @var DateTime */
    private $occurredOn;

    /**
     * @param string $body
     * @param string $type
     * @param DateTime $occurredOn
     */
    public function __construct($body, $type, DateTime $occurredOn)
    {
        $this->body = $body;
        $this->type = $type;
        $this->occurredOn = $occurredOn;
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
