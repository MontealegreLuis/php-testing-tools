<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\Controllers;

class SlimController
{
    /** @var mixed */
    private $controller;

    /**
     * @param mixed $controller
     */
    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public function __call($action, array $args)
    {
        $this->controller->$action(...$args);

        echo $this->controller->responder()->response()->getBody();
    }
}
