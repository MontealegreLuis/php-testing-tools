<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace DataBuilders;

use Application\DataBuilders\Messaging\PublishedMessageBuilder;
use Application\DataBuilders\DomainEvents\StoredEventBuilder;
use Ewallet\DataBuilders\MembersBuilder;
use Ewallet\DataBuilders\TransferWasMadeBuilder;

class A
{
    public static function member(): MembersBuilder
    {
        return new MembersBuilder();
    }

    public static function transferWasMadeEvent(): TransferWasMadeBuilder
    {
        return new TransferWasMadeBuilder();
    }

    public static function storedEvent(): StoredEventBuilder
    {
        return new StoredEventBuilder();
    }

    public static function publishedMessage(): PublishedMessageBuilder
    {
        return new PublishedMessageBuilder();
    }
}
