<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Dotenv\Dotenv;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

require __DIR__ . '/vendor/autoload.php';

if (getenv('ENV') == 'testing') {
    $environment = new Dotenv(__DIR__, '.env.tests');
    $environment->load();
    $environment->required([
        'APP_ENV',
    ]);

    $options = require __DIR__ . '/config.tests.php';
} else {
    $environment = new Dotenv(__DIR__);
    $environment->load();
    $environment->required([
        'APP_ENV',
        'MYSQL_USER',
        'MYSQL_PASSWORD',
        'MYSQL_HOST'
    ]);

    $options = require __DIR__ . '/config.php';
}

$setup = new class() {
    use ProvidesDoctrineSetup;
};
$setup->_setUpDoctrine($options);

return $setup->entityManager();

