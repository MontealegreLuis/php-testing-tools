<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DataBuilders;

class A
{
    /**
     * @return StoredEventBuilder
     */
    public static function storedEvent(): StoredEventBuilder
    {
        return new StoredEventBuilder();
    }

    /**
     * @return PublishedMessageBuilder
     */
    public static function publishedMessage(): PublishedMessageBuilder
    {
        return new PublishedMessageBuilder();
    }
}
