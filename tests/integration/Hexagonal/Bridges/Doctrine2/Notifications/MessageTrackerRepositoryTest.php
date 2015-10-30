<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\Doctrine2\Notifications;

use Ewallet\Bridges\Tests\ProvidesDoctrineSetup;
use Hexagonal\Notifications\MessageTracker;
use Hexagonal\Notifications\MessageTrackerTest;
use Hexagonal\Notifications\PublishedMessage;

class MessageTrackerRepositoryTest extends MessageTrackerTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    public function cleanupMessages()
    {
        $this->_setUpDoctrine();

        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . PublishedMessage::class)
            ->execute()
        ;
    }

    /**
     * @return MessageTracker
     */
    function messageTracker()
    {
        return $this->entityManager->getRepository(PublishedMessage::class);
    }
}
