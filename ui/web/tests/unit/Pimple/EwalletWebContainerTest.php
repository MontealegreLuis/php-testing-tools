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
use Symfony\Component\Finder\Finder;

final class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_controllers()
    {
        $basePath = new BasePath(new SplFileInfo(__DIR__ . '/../../../'));
        $container = ContainerFactory::new($basePath, new Environment('test', true));
        $finder = new Finder();
        $files = $finder->files()->name('*.php')->in(__DIR__ . '/../../../src/UI/Slim/Controllers');
        foreach ($files as $file) {
            $className = "UI\\Slim\\Controllers\\{$file->getFilenameWithoutExtension()}";
            $this->assertInstanceOf($className, $container->get($className));
        }
    }
}
