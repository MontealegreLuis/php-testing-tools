<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Dotenv\Dotenv;
use Codeception\Util\Autoload;

Autoload::addNamespace('Page', __DIR__. '/_support/_pages');

$environment = Dotenv::create(__DIR__ . '/../', '.env.tests');
$environment->load();
$environment->required(['APP_ENV', 'DB_URL']);
