<?php
/**
 * PHP version 7.0
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
    public function __construct(string $body, string $type, DateTime $occurredOn)
    {
        $this->body = $body;
        $this->type = $type;
        $this->occurredOn = $occurredOn;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
