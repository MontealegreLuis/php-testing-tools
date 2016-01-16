<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace DataBuilders;

use DataBuilders\Hexagonal\PublishedMessageBuilder;
use DataBuilders\Hexagonal\StoredEventBuilder;
use Ewallet\DataBuilders\MembersBuilder;
use Ewallet\DataBuilders\TransferWasMadeBuilder;

class A
{
    /**
     * @return MembersBuilder
     */
    public static function member()
    {
        return new MembersBuilder();
    }

    /**
     * @return TransferWasMadeBuilder
     */
    public static function transferWasMadeEvent()
    {
        return new TransferWasMadeBuilder();
    }

    /**
     * @return StoredEventBuilder
     */
    public static function storedEvent()
    {
        return new StoredEventBuilder();
    }

    /**
     * @return PublishedMessageBuilder
     */
    public static function publishedMessage()
    {
        return new PublishedMessageBuilder();
    }
}
