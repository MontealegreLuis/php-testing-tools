<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Dotenv\Dotenv;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

require __DIR__ . '/vendor/autoload.php';

$setup = new class() { use ProvidesDoctrineSetup; };
$setup->_setUpDoctrine(require __DIR__ . '/options.php');

return $setup->_entityManager();

