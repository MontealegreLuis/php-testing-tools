<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Application\Messaging\MessagePublisher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PublishMessagesCommandTest extends TestCase
{
    use MockeryPHPUnitIntegration;

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

        $this->assertRegExp(
            '/3 messages published/',
            $tester->getDisplay()
        );
    }
}
