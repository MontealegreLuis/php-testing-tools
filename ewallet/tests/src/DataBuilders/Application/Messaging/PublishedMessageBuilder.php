<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Application\Messaging;

use Application\Messaging\PublishedMessage;
use Faker\Factory;
use ReflectionClass;

class PublishedMessageBuilder
{
    /** @var Factory */
    private $factory;

    /** @var string */
    private $exchangeName;

    /** @var integer */
    private $mostRecentMessageId;

    /** @var integer */
    private $identifier;

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

        if ($this->identifier) {
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
        $this->mostRecentMessageId = $this->factory->numberBetween(1, 10000);
    }

    private function assignIdentifierTo(PublishedMessage $message): void
    {
        $class = new ReflectionClass($message);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($message, $this->identifier);
    }
}
