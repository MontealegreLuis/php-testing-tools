<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

final class DataStorageSetup
{
    private static bool $migrated = false;

    private EntityManagerInterface $entityManager;

    /**
     * Setup XML mapping configuration, configure entity manager, add custom types
     *
     * @param mixed[] $options
     * @throws Exception
     * @throws ORMException
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
            if (! Type::hasType($type)) {
                Type::addType($type, $class);
            }
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
