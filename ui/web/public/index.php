<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Adapters\Symfony\DependencyInjection\ContainerFactory;
use Dotenv\Dotenv;
use Framework\Slim\ApplicationFactory;

(static function () {
    if (!isset($_ENV['APP_ENV'])) {
        // We' re not running the application from the containers
        $environment = Dotenv::createImmutable(__DIR__ . '/../');
        $environment->load();
        $environment->required(['APP_ENV', 'DB_URL', 'PDO_DRIVER']);
    }

    $app = ApplicationFactory::createFromContainer(ContainerFactory::new());
    $app->run();
})();
