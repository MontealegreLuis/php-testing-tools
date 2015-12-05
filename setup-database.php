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

$connection->exec('DROP DATABASE IF EXISTS ewallet_db');
$sql = <<<DATABASE
    CREATE DATABASE ewallet_db
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci
DATABASE;
$connection->exec($sql);

$connection->exec(
    "GRANT ALL PRIVILEGES ON ewallet_db.* TO {$parameters[3]}@{$parameters[2]} IDENTIFIED BY '{$parameters[4]}'"
);
echo "Database user {$parameters[3]} created.";
