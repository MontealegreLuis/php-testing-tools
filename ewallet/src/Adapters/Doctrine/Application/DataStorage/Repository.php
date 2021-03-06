<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Doctrine\Application\DataStorage;

use Doctrine\ORM\EntityManager;

abstract class Repository
{
    protected EntityManager $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }
}
