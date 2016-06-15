<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Messaging;

class PublishedMessage
{
    /** @var integer */
    private $id;

    /** @var string */
    private $exchangeName;

    /** @var int */
    private $mostRecentMessageId;

    /**
     * @param string $exchangeName
     * @param int $mostRecentMessageId
     */
    public function __construct(string $exchangeName, int $mostRecentMessageId)
    {
        $this->exchangeName = $exchangeName;
        $this->mostRecentMessageId = $mostRecentMessageId;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    /**
     * @return int
     */
    public function mostRecentMessageId(): int
    {
        return $this->mostRecentMessageId;
    }

    /**
     * @param int $mostRecentMessageId
     */
    public function updateMostRecentMessageId(int $mostRecentMessageId)
    {
        $this->mostRecentMessageId = $mostRecentMessageId;
    }

    /**
     * 2 messages are equal if they have the same ID
     *
     * @param PublishedMessage $message
     * @return bool
     */
    public function equals(PublishedMessage $message): bool
    {
        return $this->id == $message->id;
    }
}
