<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Slim;

use Ewallet\Slim\ControllerProviders\EwalletControllerProvider;

class Controllers extends \ComPHPPuebla\Slim\Controllers
{
    protected function init()
    {
        $this
            ->add(new EwalletControllerProvider())
        ;
    }
}
