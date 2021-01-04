<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application;

use SplFileInfo;
use Webmozart\Assert\Assert;

final class BasePath
{
    private SplFileInfo $path;

    public function __construct(SplFileInfo $path)
    {
        $this->setPath($path);
    }

    public function absolutePath(): string
    {
        return (string) $this->path->getRealPath();
    }

    public function configPath(): string
    {
        return "{$this->path->getRealPath()}/config";
    }

    public function cachePath(): string
    {
        return "{$this->path->getRealPath()}/var";
    }

    private function setPath(SplFileInfo $path): void
    {
        Assert::true($path->isDir(), 'Application base path must be a directory. Got %s');
        Assert::true($path->isReadable(), '%s is not readable a directory');
        $this->path = $path;
    }
}
