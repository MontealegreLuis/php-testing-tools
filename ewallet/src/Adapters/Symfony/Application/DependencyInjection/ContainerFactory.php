<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Application\DependencyInjection;

use Application\BasePath;
use Application\Environment;
use Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ContainerFactory
{
    private static ?ContainerInterface $container = null;

    public static function new(BasePath $basePath, Environment $environment): ContainerInterface
    {
        if (self::$container === null) {
            self::$container = self::create($basePath, $environment);
        }
        return self::$container;
    }

    public static function create(BasePath $basePath, Environment $environment): ContainerInterface
    {
        $descriptor = ContainerDescriptor::forEnvironment($environment);
        $file = "{$basePath->cachePath()}/{$descriptor->filename()}";
        $cache = new ConfigCache($file, $environment->debug());
        if (! $cache->isFresh()) {
            self::buildContainer($basePath, $cache, $environment, $descriptor);
        }
        require_once $file;
        $className = $descriptor->fqcn();
        return new $className();
    }

    /** @throws Exception */
    private static function buildContainer(
        BasePath $basePath,
        ConfigCache $cache,
        Environment $environment,
        ContainerDescriptor $descriptor
    ): void {
        $builder = new ContainerBuilder();
        $loader = new YamlFileLoader($builder, new FileLocator($basePath->configPath()));
        $builder->setParameter('app.debug', $environment->debug());
        $builder->setParameter('app.base_path', $basePath->absolutePath());
        $loader->load("parameters.{$environment->name()}.yml");
        $loader->load("app.{$environment->name()}.yml");
        $builder->compile();
        $dumper = new PhpDumper($builder);
        /** @var string $content */
        $content = $dumper->dump([
            'class' => $descriptor->name(),
            'namespace' => $descriptor->namespace(),
        ]);
        $cache->write($content, $builder->getResources());
    }
}
