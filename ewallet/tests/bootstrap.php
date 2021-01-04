<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Application\BasePath;
use Dotenv\Dotenv;

(static function(): void {
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
