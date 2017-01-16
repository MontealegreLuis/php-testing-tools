<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Dotenv\Dotenv;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

require __DIR__ . '/vendor/autoload.php';

$environment = new Dotenv(__DIR__);
$environment->load();
$environment->required([
    'APP_ENV',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST'
]);

$setup = new class() { use ProvidesDoctrineSetup; };
$setup->_setUpDoctrine(require __DIR__ . '/config.php');

return $setup->_entityManager();
