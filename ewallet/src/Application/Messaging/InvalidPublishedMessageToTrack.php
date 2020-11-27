<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\Messaging;

use InvalidArgumentException;

final class InvalidPublishedMessageToTrack extends InvalidArgumentException
{
    public static function isNotTheMostRecent(
        PublishedMessage $mostRecentMessage,
        PublishedMessage $invalidMessageToTrack
    ): InvalidPublishedMessageToTrack {
        return new InvalidPublishedMessageToTrack(sprintf(
            'Cannot track message with ID %s, most recent message has ID %s',
            $invalidMessageToTrack->mostRecentMessageId(),
            $mostRecentMessage->mostRecentMessageId()
        ));
    }
}
