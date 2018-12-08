<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

class DataStorageSetup
{
    private static $migrated = false;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * Setup XML mapping configuration, configure entity manager, add custom types
     *
     * @throws DBAL\DBALException
     * @throws ORM\ORMException
     */
    public function __construct(array $options)
    {
        $configuration = Setup::createXMLMetadataConfiguration(
            $options['doctrine']['mapping_dirs'],
            $options['doctrine']['dev_mode'],
            $options['doctrine']['proxy_dir']
        );
        $this->entityManager = EntityManager::create($options['doctrine']['connection'], $configuration);

        $platform = $this->entityManager->getConnection()->getDatabasePlatform();
        foreach ($options['doctrine']['types'] as $type => $class) {
            !Type::hasType($type) && Type::addType($type, $class);
            $platform->registerDoctrineTypeMapping($type, $type);
        }
    }

    public function entityManager(): EntityManagerInterface
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
