<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Hexagonal\Doctrine2\Messaging;

use Hexagonal\ContractTests\Messaging\MessageTrackerTest;
use Hexagonal\Messaging\MessageTracker;
use Hexagonal\Messaging\PublishedMessage;
use Ewallet\Doctrine\ProvidesDoctrineSetup;
use Ports\Doctrine\Messaging\MessageTrackerRepository;

class MessageTrackerRepositoryTest extends MessageTrackerTest
{
    use ProvidesDoctrineSetup;

    function messageTracker(): MessageTracker
    {
        $this->cleanUpMessages();

        return new MessageTrackerRepository(self::$entityManager);
    }

    private function cleanUpMessages(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');

        self::$entityManager
            ->createQuery('DELETE FROM ' . PublishedMessage::class)
            ->execute()
        ;
    }
}
