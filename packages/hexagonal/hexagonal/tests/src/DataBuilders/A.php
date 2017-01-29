<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\DataBuilders;

class A
{
    public static function storedEvent(): StoredEventBuilder
    {
        return new StoredEventBuilder();
    }

    public static function publishedMessage(): PublishedMessageBuilder
    {
        return new PublishedMessageBuilder();
    }
}
