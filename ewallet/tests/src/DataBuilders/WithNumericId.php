<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders;

use ReflectionClass;
use ReflectionException;

trait WithNumericId
{
    private static int $nextId = 1;

    /** @throws ReflectionException */
    public function assignId(object $entity, int $nextId = null): void
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $nextId ?? self::$nextId++);
    }
}
