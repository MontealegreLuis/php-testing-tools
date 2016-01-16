<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace DataBuilders;

use DataBuilders\Hexagonal\PublishedMessageBuilder;
use DataBuilders\Hexagonal\StoredEventBuilder;

class A
{
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
