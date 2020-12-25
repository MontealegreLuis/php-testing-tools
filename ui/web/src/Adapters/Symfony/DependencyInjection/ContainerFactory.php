<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\DependencyInjection;

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

    /** @throws Exception */
    public static function new(): ContainerInterface
    {
        if (self::$container === null) {
            self::$container = self::createContainer();
        }
        return self::$container;
    }

    private static function createContainer(): WebApplicationContainer
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'dev';
        $appDebug = $_ENV['APP_DEBUG'] === 'true';
        $file = __DIR__ . "/../../../../var/container-{$appEnv}.php";
        $cache = new ConfigCache($file, $appDebug);
        if (! $cache->isFresh()) {
            self::buildContainer($cache, $appDebug);
        }
        require_once $file;
        return new WebApplicationContainer();
    }

    /** @throws Exception */
    private static function buildContainer(ConfigCache $cache, bool $appDebug): void
    {
        $builder = new ContainerBuilder();
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../../../../config'));
        $builder->setParameter('app.debug', $appDebug);
        $builder->setParameter('app.base_path', __DIR__ . '/../../../../');
        $loader->load('app.yml');
        $builder->compile();
        $dumper = new PhpDumper($builder);
        /** @var string $content */
        $content = $dumper->dump([
            'class' => 'WebApplicationContainer',
            'namespace' => 'Adapters\\Symfony\\DependencyInjection',
        ]);
        $cache->write($content, $builder->getResources());
    }
}
