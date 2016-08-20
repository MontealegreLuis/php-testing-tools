<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Ewallet\Slim\Controllers\TransferFundsController;
use Ewallet\Slim\Middleware\{RequestLoggingMiddleware, StoreEventsMiddleware};
use Ewallet\Twig\RouterExtension;
use Ewallet\ManageWallet\TransferFundsFormResponder;
use Ewallet\EasyForms\TransferFundsForm;
use Hexagonal\Doctrine2\DomainEvents\EventStoreRepository;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Slim\{App, Http\Environment};
use Twig_Environment as TwigEnvironment;
use Twig_Loader_Filesystem as Loader;

class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_services()
    {
        $options = require __DIR__ . '/../../../config.tests.php';
        $container = new EwalletWebContainer($options, new App());

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

        $this->assertInstanceOf(
            LoggerInterface::class,
            $container['slim.logger']
        );
        $this->assertInstanceOf(
            EventStoreRepository::class,
            $container['ewallet.event_store']
        );
        $this->assertInstanceOf(
            PersistEventsSubscriber::class,
            $container['ewallet.event_persist_subscriber']
        );
        $this->assertInstanceOf(
            RequestLoggingMiddleware::class,
            $container['slim.middleware.request_logging']
        );
        $this->assertInstanceOf(
            StoreEventsMiddleware::class,
            $container['slim.middleware.store_events']
        );
    }
}
