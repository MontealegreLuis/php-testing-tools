<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Notifications;

interface MessageTracker
{
    /**
     * @param string $exchangeName
     * @return PublishedMessage
     */
    public function mostRecentPublishedMessage($exchangeName);

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);
}
