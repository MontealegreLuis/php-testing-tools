<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use DateTime;

/**
 * Representation of a persisted domain event
 */
class StoredEvent implements DomainEvent
{
    /** @var integer */
    private $id;

    /** @var string */
    private $body;

    /** @var string */
    private $type;

    /** @var DateTime */
    private $occurredOn;

    public function __construct(string $body, string $type, DateTime $occurredOn)
    {
        $this->body = $body;
        $this->type = $type;
        $this->occurredOn = $occurredOn;
    }

    public function id(): int
    {
        return (int) $this->id;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
