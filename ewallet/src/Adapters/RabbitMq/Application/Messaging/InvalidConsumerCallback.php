<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\RabbitMq\Application\Messaging;

use RuntimeException;

final class InvalidConsumerCallback extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Provided callback cannot be called');
    }
}
