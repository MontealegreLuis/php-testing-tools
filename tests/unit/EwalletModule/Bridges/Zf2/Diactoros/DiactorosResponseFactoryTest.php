<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Diactoros;

use PHPUnit_Framework_TestCase as TestCase;

class DiactorosResponseFactoryTest extends TestCase
{
    /** @test */
    function it_should_create_a_response_with_an_http_ok_code()
    {
        $factory = new DiactorosResponseFactory();

        $response = $factory->buildResponse(
            "<html><head></head><body><h1>Hello world!</h1></body></html>"
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}
