<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Mail;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\Smtp;

class CreateTransportBasedOnConfigurationTest extends TestCase
{
    /** @test */
    function it_should_create_file_transport()
    {
        $factory = new TransportFactory();

        $transport = $factory->buildTransport([
            'type' => 'file',
            'options' => [
                'path' => __DIR__,
            ],
        ]);

        $this->assertInstanceOf(File::class, $transport);
        $this->assertEquals(__DIR__, $transport->getOptions()->getPath());
    }

    /** @test */
    function it_should_create_smtp_transport()
    {
        $factory = new TransportFactory();

        $transport = $factory->buildTransport([
            'type' => 'smtp',
            'options' => [
                'host' => '127.0.0.1',
                'port' => 1025,
            ],
        ]);

        $this->assertInstanceOf(Smtp::class, $transport);
    }
}
