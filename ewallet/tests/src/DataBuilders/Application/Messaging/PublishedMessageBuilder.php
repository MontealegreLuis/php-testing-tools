<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Application\Messaging;

use Application\Messaging\PublishedMessage;
use Faker\Factory;
use Faker\Generator;
use ReflectionClass;

final class PublishedMessageBuilder
{
    private Generator $factory;

    private string $exchangeName;

    private int $mostRecentMessageId;

    private ?int $identifier = null;

    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

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

    public function build(): PublishedMessage
    {
        $message = new PublishedMessage($this->exchangeName, $this->mostRecentMessageId);
        if ($this->identifier !== null) {
            $this->assignIdentifierTo($message);
        }

        $this->reset();

        return $message;
    }

    public function withId(int $identifier): PublishedMessageBuilder
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function reset(): void
    {
        $this->identifier = null;
        $this->exchangeName = $this->factory->word;
        $this->mostRecentMessageId = $this->factory->numberBetween(1, 10_000);
    }

    private function assignIdentifierTo(PublishedMessage $message): void
    {
        $class = new ReflectionClass($message);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($message, $this->identifier);
    }
}
