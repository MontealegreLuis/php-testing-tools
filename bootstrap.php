<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require __DIR__ . '/vendor/autoload.php';

$options = require __DIR__ . '/app/config.php';

$configuration = Setup::createXMLMetadataConfiguration(
    $options['doctrine']['mapping_dirs'],
    $options['doctrine']['dev_mode'],
    $options['doctrine']['proxy_dir']
);
$entityManager = EntityManager::create(
    $options['doctrine']['connection'], $configuration
);

$platform = $entityManager->getConnection()->getDatabasePlatform();
foreach ($options['doctrine']['types'] as $type => $class) {
    Type::addType($type, $class);
    $platform->registerDoctrineTypeMapping($type, $type);
}
