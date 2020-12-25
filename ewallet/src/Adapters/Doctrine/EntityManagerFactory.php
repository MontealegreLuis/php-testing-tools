<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerFactory
{
    /**
     * @param mixed[] $config
     * @throws ORMException
     * @throws Exception
     */
    public static function create(array $config): EntityManager
    {
        $configuration = Setup::createXMLMetadataConfiguration(
            $config['mapping_dirs'],
            $config['dev_mode'],
            $config['proxy_dir']
        );
        $entityManager = EntityManager::create($config['connection'], $configuration);

        $platform = $entityManager->getConnection()->getDatabasePlatform();
        foreach ($config['types'] as $type => $class) {
            if (! Type::hasType($type)) {
                Type::addType($type, $class);
                $platform->registerDoctrineTypeMapping($type, $type);
            }
        }

        return $entityManager;
    }
}
