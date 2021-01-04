<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Adapters\Symfony\Application\DependencyInjection\ContainerFactory;
use Application\BasePath;
use Application\Environment;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

final class EwalletConsoleContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_console_application_services()
    {
        $basePath = new BasePath(new SplFileInfo(__DIR__ . '/../../../'));
        $container = ContainerFactory::create($basePath, new Environment('test', true));
        $finder = new Finder();
        $files = $finder->files()->name('*.php')->in(__DIR__ . '/../../../src/UI/Console/Commands');
        foreach ($files as $file) {
            $className = "Ewallet\\UI\\Console\\Commands\\{$file->getFilenameWithoutExtension()}";
            $this->assertInstanceOf($className, $container->get($className));
        }


        $this->assertInstanceOf(ArgvInput::class, $container->get(InputInterface::class));
        $this->assertInstanceOf(ConsoleOutput::class, $container->get(OutputInterface::class));
    }
}
