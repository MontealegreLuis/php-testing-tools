<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Adapters\Symfony\DependencyInjection\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

final class EwalletWebContainerTest extends TestCase
{
    /** @test */
    function it_creates_the_web_application_controllers()
    {
        $container = ContainerFactory::new();
        $finder = new Finder();
        $files = $finder->files()->name('*.php')->in(__DIR__ . '/../../../src/UI/Slim/Controllers');
        foreach ($files as $file) {
            $className = "UI\\Slim\\Controllers\\{$file->getFilenameWithoutExtension()}";
            $this->assertInstanceOf($className, $container->get($className));
        }
    }
}
