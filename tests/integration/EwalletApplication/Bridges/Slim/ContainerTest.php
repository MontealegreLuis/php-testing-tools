<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use ComPHPPuebla\Slim\Resolver;
use Doctrine\ORM\EntityManager;
use Ewallet\Bridges\Doctrine2\Accounts\MembersRepository;
use EwalletApplication\Bridges\Slim\Controllers\SlimController;
use EwalletModule\Bridges\Zf2InputFilter\TransferFundsInputFilterRequest;
use EwalletModule\Controllers\TransferFundsResponder;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use PHPUnit_Framework_TestCase as TestCase;
use Slim\Slim;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

class ContainerTest extends TestCase
{
    /** @test */
    function it_should_create_the_application_services()
    {
        $app = new Slim();
        // Request objects need environment information
        \Slim\Environment::mock(['REQUEST_METHOD' => 'POST']);

        $services = new Services(
            new Resolver(), require __DIR__ . '/../../../../../app/config.php'
        );

        $services->configure($app);

        $this->assertInstanceOf(
            EntityManager::class, $app->container->get('doctrine.em')
        );
        $this->assertInstanceOf(
            Environment::class, $app->container->get('twig.environment')
        );
        $this->assertInstanceOf(
            Loader::class, $app->container->get('twig.loader')
        );
        $this->assertInstanceOf(
            MembersRepository::class,
            $app->container->get('ewallet.member_repository')
        );
        $this->assertInstanceOf(
            TransferFundsForm::class,
            $app->container->get('ewallet.transfer_form')
        );
        $this->assertInstanceOf(
            MembersConfiguration::class,
            $app->container->get('ewallet.members_configuration')
        );
        $this->assertInstanceOf(
            TransferFundsResponder::class,
            $app->container->get('ewallet.transfer_funds_responder')
        );
        $this->assertInstanceOf(
            SlimController::class,
            $app->container->get('ewallet.transfer_form_controller')
        );
        $this->assertInstanceOf(
            SlimController::class,
            $app->container->get('ewallet.transfer_funds_controller')
        );
        $this->assertInstanceOf(
            TransferFundsInputFilterRequest::class,
            $app->container->get('ewallet.transfer_filter_request')
        );
    }
}
