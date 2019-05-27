<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DomainEvents;

use DateTimeInterface;

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

    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(string $body, string $type, DateTimeInterface $occurredOn)
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

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
