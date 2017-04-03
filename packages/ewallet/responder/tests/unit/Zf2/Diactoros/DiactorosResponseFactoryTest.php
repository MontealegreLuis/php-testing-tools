<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\Diactoros;

use PHPUnit\Framework\TestCase;

class DiactorosResponseFactoryTest extends TestCase
{
    /** @test */
    function it_creates_a_response_with_an_http_ok_code()
    {
        $factory = new DiactorosResponseFactory();

        $response = $factory->buildResponse(
            '<html><head></head><body><h1>Hello world!</h1></body></html>'
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}
