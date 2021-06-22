<?php
namespace Jalno\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Jalno\Lumen\Contracts\{IStorage, IPackage};

class Repository implements IStorage
{
    protected IPackage $package;

    /** @var array<string, Filesystem> $disks */
    private array $disks = [];

    public function __construct(IPackage $package)
    {
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
            if (!isset($this->package->getStorageConfig()[$name])) {
                throw new \InvalidArgumentException($name . " disk is undifined.");
            }
            $this->disks[$name] = app("filesystem")->createLocalDriver($this->package->getStorageConfig()[$name]);
        }

        return $this->disks[$name];
    }
}
