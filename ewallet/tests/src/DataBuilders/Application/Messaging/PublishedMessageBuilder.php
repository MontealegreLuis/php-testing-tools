<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Application\Messaging;

use Application\Messaging\PublishedMessage;
use DataBuilders\Random;
use DataBuilders\WithNumericId;

final class PublishedMessageBuilder
{
    use WithNumericId;

    private ?int $identifier = null;

    private ?string $exchangeName = null;

    private ?int $mostRecentMessageId = null;

    public function withExchangeName(string $name): PublishedMessageBuilder
    {
        $this->exchangeName = $name;
        return $this;
    }

    public function withMostRecentMessageId(int $id): PublishedMessageBuilder
    {
        $this->mostRecentMessageId = $id;
        return $this;
    }

    public function withId(int $identifier): PublishedMessageBuilder
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function build(): PublishedMessage
    {
        $message = new PublishedMessage(
            $this->exchangeName ?? Random::word(),
            $this->mostRecentMessageId ?? Random::numericId()
        );
        $this->assignId($message, $this->identifier);

        return $message;
    }
}
