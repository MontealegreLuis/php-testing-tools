<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

final class DataStorageSetup
{
    private static bool $migrated = false;

    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function entityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function updateSchema(): void
    {
        if (self::$migrated) {
            return; // Do not modify the schema twice
        }
        $tool = new SchemaTool($this->entityManager);
        $tool->updateSchema($this->entityManager->getMetadataFactory()->getAllMetadata(), true);
        self::$migrated = true;
    }
}
