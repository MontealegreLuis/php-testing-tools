<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Adapters\Symfony\Application\DependencyInjection\ContainerFactory;
use Application\BasePath;
use Application\Environment;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class EwalletMessagingServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_the_services_for_the_messaging_application()
    {
        $basePath = new BasePath(new SplFileInfo(__DIR__ . '/../../../../'));
        $container = ContainerFactory::create($basePath, new Environment('test', true));
        $finder = new Finder();
        $files = $finder->files()->name('*.php')->in(__DIR__ . '/../../../../src/Ui/Console/Commands');
        foreach ($files as $file) {
            $className = "Ewallet\\Ui\\Console\\Commands\\{$file->getFilenameWithoutExtension()}";
            $this->assertInstanceOf($className, $container->get($className));
        }
    }
}
