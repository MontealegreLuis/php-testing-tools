<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Doctrine2\Messaging;

use Hexagonal\ContractTests\Messaging\MessageTrackerTest;
use Hexagonal\Messaging\{MessageTracker, PublishedMessage};
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class MessageTrackerRepositoryTest extends MessageTrackerTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    public function cleanupMessages()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');

        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . PublishedMessage::class)
            ->execute()
        ;
    }

    /**
     * @return MessageTracker
     */
    function messageTracker(): MessageTracker
    {
        return $this->entityManager->getRepository(PublishedMessage::class);
    }
}
