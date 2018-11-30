<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ports\Doctrine\Application\Messaging;

use Application\ContractTests\Messaging\MessageTrackerTest;
use Application\Messaging\MessageTracker;
use Application\Messaging\PublishedMessage;
use Doctrine\ProvidesDoctrineSetup;

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
        $this->_setUpDoctrine(require __DIR__ . '/../../../../../../config.php');

        self::$entityManager
            ->createQuery('DELETE FROM ' . PublishedMessage::class)
            ->execute()
        ;
    }
}
