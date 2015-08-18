<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple;

use EwalletApplication\Bridges\Slim\Controllers\SlimController;
use EwalletModule\Bridges\Zf2\InputFilter\TransferFundsInputFilterRequest;
use EwalletModule\Controllers\TransferFundsResponder;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use PHPUnit_Framework_TestCase as TestCase;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_web_application_services()
    {
        $options = require __DIR__ . '/../../../../../app/config.php';
        $container = new EwalletWebContainer($options);

        $this->assertInstanceOf(
            Environment::class,
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
            MembersConfiguration::class,
            $container['ewallet.members_configuration']
        );
        $this->assertInstanceOf(
            TransferFundsResponder::class,
            $container['ewallet.transfer_funds_responder']
        );
        $this->assertInstanceOf(
            SlimController::class,
            $container['ewallet.transfer_form_controller']
        );
        $this->assertInstanceOf(
            SlimController::class,
            $container['ewallet.transfer_funds_controller']
        );
        $this->assertInstanceOf(
            TransferFundsInputFilterRequest::class,
            $container['ewallet.transfer_filter_request']
        );
    }
}
