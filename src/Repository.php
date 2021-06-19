<?php
namespace Jalno\Storage;

use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;
use Jalno\Lumen\Contracts\{IStorage, IPackage};
use Illuminate\Contracts\Filesystem\Filesystem;

class Repository implements IStorage
{
    protected Container $app;
    protected IPackage $package;
    private array $disks = [];

    public function __construct(Container $app, IPackage $package)
    {
        $this->app = $app;
        $this->package = $package;
    }

    public function public(): Filesystem
    {
        return $this->disk("public");
    }

    public function private(): Filesystem
    {
        return $this->disk("private");
    }

    public function disk(string $name): Filesystem
    {
        if (!isset($this->disks[$name])) {
            if (!$this->app["config"]->has("filesystems")) {
                $this->app->configure("filesystems");
            }
            $packageName = str_replace("/", ".", $this->package->getName());
            $this->app["config"]->set("filesystems.disks.{$packageName}.{$name}", $this->getConfig($name));
            $this->disks[$name] = \Storage::disk("{$packageName}.{$name}");
        }

        return $this->disks[$name];
    }

    /**
     * Get the filesystem connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        return $this->package->getStorageConfig()[$name] ?? [];
    }
}
