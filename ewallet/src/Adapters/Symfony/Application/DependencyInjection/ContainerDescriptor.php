<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Application\DependencyInjection;

use Application\Environment;

final class ContainerDescriptor
{
    private string $name;

    private string $namespace;

    private string $filename;

    public static function forEnvironment(Environment $environment): ContainerDescriptor
    {
        return new self(
            'ApplicationContainer',
            'Adapters\\Symfony\\DependencyInjection',
            "container-{$environment->name()}.php"
        );
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function fqcn(): string
    {
        return "{$this->namespace}\\{$this->name}";
    }

    public function __construct(string $name, string $namespace, string $filename)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->filename = $filename;
    }
}
