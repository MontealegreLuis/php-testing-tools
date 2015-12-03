<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
$parameters = array_slice($argv, 1);
$connection = new PDO("mysql:host={$parameters[2]}", $parameters[0], $parameters[1], [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$connection->exec("GRANT ALL PRIVILEGES on {$parameters[3]}.* TO {$parameters[4]}@{$parameters[2]} IDENTIFIED BY '{$parameters[5]}'");
echo "Database user {$parameters[4]} created";
