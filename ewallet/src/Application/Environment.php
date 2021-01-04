<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application;

use Dotenv\Dotenv;
use Webmozart\Assert\Assert;

final class Environment
{
    private string $name;

    private bool $debug;

    public static function fromGlobals(BasePath $basePath): Environment
    {
        self::load($basePath);
        $name = trim($_ENV['APP_ENV'] ?? 'local');
        $envDebug = trim($_ENV['APP_DEBUG'] ?? 'false');
        $debug = $envDebug === 'true' || $envDebug === '1';

        return new self($name, $debug);
    }

    private static function load(BasePath $basePath): void
    {
        if (! isset($_ENV['APP_ENV'])) {
            $environment = Dotenv::createImmutable($basePath->absolutePath());
            $environment->load();
        }
    }

    public function __construct(string $name, bool $debug)
    {
        $this->setName($name);
        $this->debug = $debug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function debug(): bool
    {
        return $this->debug;
    }

    private function setName(string $name): void
    {
        Assert::oneOf(
            $name,
            ['local', 'test', 'dev', 'prod'],
            'Expected one of the valid application environments: %2$s. Got %s'
        );
        $this->name = $name;
    }
}
