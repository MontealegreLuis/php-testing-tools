<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Application\BasePath;
use Dotenv\Dotenv;
use Codeception\Util\Autoload;

(static function () {
    Autoload::addNamespace('Page', __DIR__. '/_support/_pages');

    $basePath = new BasePath(new SplFileInfo(__DIR__ . '/../'));
    if (file_exists($basePath->cachePath() . '/container-test.php')) {
        unlink($basePath->cachePath() . '/container-test.php');
    }
    if (file_exists($basePath->cachePath() . '/container-test.php.meta')) {
        unlink($basePath->cachePath() . '/container-test.php.meta');
    }
    $environment = Dotenv::createImmutable($basePath->absolutePath(), '.env.test');
    $environment->load();
})();
