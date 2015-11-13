<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Bridges\JmsSerializer;

use DateTime;
use Ewallet\Accounts\Identifier;
use Fakes\Hexagonal\DomainEvents\InstantaneousEvent;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

class JsonSerializerTest extends TestCase
{
    /** @test */
    function it_should_serialize_a_domain_event_to_json()
    {
        $serializer = new JsonSerializer();
        $anEvent = new InstantaneousEvent(
            Identifier::fromString('abc'),
            Money::MXN(10000),
            new DateTime('2015-10-24 12:39:51')
        );

        $json = $serializer->serialize($anEvent);

        $this->assertEquals(
            '{"occurred_on":"2015-10-24 12:39:51","member_id":"abc","amount":10000}',
            $json,
            'JSON format for serialized event is incorrect'
        );
    }
}
