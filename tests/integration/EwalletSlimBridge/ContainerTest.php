<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletSlimBridge;

use ComPHPPuebla\Slim\Resolver;
use Doctrine\ORM\EntityManager;
use EwalletDoctrineBridge\Accounts\MembersRepository;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use EwalletSlimBridge\Controllers\SlimController;
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
        $services = new Services(
            new Resolver(), require __DIR__ . '/../../../app/config.php'
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
            SlimController::class,
            $app->container->get('ewallet.transfer_form_controller')
        );
        $this->assertInstanceOf(
            SlimController::class,
            $app->container->get('ewallet.transfer_funds_controller')
        );
    }
}
