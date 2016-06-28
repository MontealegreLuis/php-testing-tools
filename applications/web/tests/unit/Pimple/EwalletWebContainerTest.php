<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Dotenv\Dotenv;
use Ewallet\Slim\Controllers\TransferFundsController;
use Ewallet\Twig\RouterExtension;
use Ewallet\Wallet\TransferFundsFormResponder;
use Ewallet\EasyForms\TransferFundsForm;
use PHPUnit_Framework_TestCase as TestCase;
use Slim\{Environment, Slim};
use Twig_Environment as TwigEnvironment;
use Twig_Loader_Filesystem as Loader;

class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_services()
    {
        Environment::mock(['REQUEST_METHOD' => 'GET']);
        $environment = new Dotenv(__DIR__ . '/../../../');
        $environment->load();
        $options = require __DIR__ . '/../../../config.php';
        $container = new EwalletWebContainer($options, new Slim());

        $this->assertInstanceOf(
            TwigEnvironment::class,
            $container['twig.environment']
        );
        $this->assertInstanceOf(
            Loader::class,
            $container['twig.loader']
        );
        $this->assertInstanceOf(
            TransferFundsForm::class,
            $container['ewallet.transfer_form']
        );
        $this->assertInstanceOf(
            TransferFundsFormResponder::class,
            $container['ewallet.transfer_funds_web_responder']
        );
        $this->assertInstanceOf(
            TransferFundsController::class,
            $container['ewallet.transfer_form_controller']
        );
        $this->assertInstanceOf(
            TransferFundsController::class,
            $container['ewallet.transfer_funds_controller']
        );
        $this->assertInstanceOf(
            RouterExtension::class,
            $container['slim.twig_extension']
        );
        $this->assertInstanceOf(
            RouterExtension::class,
            $container['twig.environment']->getExtension('slim_router')
        );
    }
}
