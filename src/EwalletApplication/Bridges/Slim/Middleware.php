<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use ComPHPPuebla\Slim\MiddlewareLayers;
use Slim\Helper\Set;

class Middleware extends MiddlewareLayers
{
    /**
     * @param Set $container
     */
    public function init(Set $container)
    {
        $this->add($container->get('slim.middleware.request_logging'));
    }
}
