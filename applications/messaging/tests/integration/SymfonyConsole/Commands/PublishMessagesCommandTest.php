<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Hexagonal\Messaging\MessagePublisher;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PublishMessagesCommandTest extends TestCase
{
    /** @test */
    function it_returns_count_of_published_messages()
    {
        $publisher = Mockery::mock(MessagePublisher::class);
        $publisher
            ->shouldReceive('publishTo')
            ->once()
            ->with('ewallet')
            ->andReturn(3)
        ;

        $tester = new CommandTester(new PublishMessagesCommand($publisher));
        $tester->execute([]);

        $this->assertRegexp(
            '/3 messages published/',
            $tester->getDisplay()
        );
    }
}
