<?php
/**
 * PHP version 7.1
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

    function messageTracker(): MessageTracker
    {
        $this->cleanUpMessages();

        return $this->entityManager->getRepository(PublishedMessage::class);
    }

    private function cleanUpMessages(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');

        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . PublishedMessage::class)
            ->execute()
        ;
    }
}
