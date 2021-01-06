<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Application\DomainEvents;

use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Application\DomainEvents\StoredEvent;
use Application\Messaging\PublishedMessage;
use DataBuilders\A;
use DataBuilders\Random;
use DataBuilders\WithNumericId;
use Ewallet\Memberships\TransferWasMade;

final class StoredEventBuilder
{
    use WithNumericId;

    private JsonSerializer $serializer;

    private ?int $id = null;

    private ?string $type;

    /**
     * By default all the stored event bodies are taken from a `TransferWasMade`
     * event built with random values
     */
    public function __construct()
    {
        $this->serializer = new JsonSerializer();
    }

    public function from(PublishedMessage $message): StoredEventBuilder
    {
        return $this->withId($message->mostRecentMessageId());
    }

    public function withId(int $id): StoredEventBuilder
    {
        $this->id = $id;

        return $this;
    }

    public function withUnknownType(): StoredEventBuilder
    {
        $this->type = 'Ewallet\UnkownEvent';

        return $this;
    }

    public function build(): StoredEvent
    {
        $event = new StoredEvent(
            $this->serializer->serialize(A::transferWasMadeEvent()->build()),
            $this->type ?? TransferWasMade::class,
            Random::date()
        );
        $this->assignId($event, $this->id ?? self::$nextId);

        return $event;
    }
}
