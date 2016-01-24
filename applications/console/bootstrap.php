<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$environment = new Dotenv(__DIR__);
$environment->load();
$environment->required([
    'DOCTRINE_DEV_MODE',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST'
]);

$options = require __DIR__ . '/config.php';

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
