<?php
namespace Jalno\Storage;

use InvalidArgumentException;
use Illuminate\Contracts\{
    Filesystem\Filesystem,
    Filesystem\Factory as FileSystemFactory,
    Container\Container,
    Config\Repository as ConfigRepository
};
use Jalno\Lumen\Contracts\{IStorage, IPackage};

class Repository implements IStorage
{
    protected Container $app;
    protected IPackage $package;
    protected FileSystemFactory $storage;
    protected ConfigRepository $config;

    /** @var array<string, Filesystem> $disks */
    private array $disks = [];

    public function __construct(Container $app, IPackage $package)
    {
        $this->app = $app;
        $this->storage = $this->app->make("filesystem");
        $this->config = $this->app->make(ConfigRepository::class);
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
            if ($this->app instanceof \Laravel\Lumen\Application and !$this->config->has("filesystems")) {
                $this->app->configure("filesystems");
            }
            $packageName = str_replace("/", ".", $this->package->getName());
            $this->config->set("filesystems.disks.{$packageName}.{$name}", $this->getConfig($name));
            $this->disks[$name] = $this->storage->disk("{$packageName}.{$name}");
        }

        return $this->disks[$name];
    }

    /**
     * Get the filesystem connection configuration.
     *
     * @param  string  $name
     * @return array<string,mixed>
     */
    protected function getConfig(string $name): array
    {
        return $this->package->getStorageConfig()[$name] ?? [];
    }
}
