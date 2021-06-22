<?php
namespace Jalno\Storage;

use Jalno\Lumen\Packages\PackageAbstract;

class Package extends PackageAbstract
{
    public function getProviders(): array
    {
        return [
            Providers\StorageServiceProvider::class,
        ];
    }

    public function basePath(): string
    {
        return __DIR__;
    }

    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    public function getCommands(): array
    {
        return [
            Console\StorageLinkCommand::class,
        ];
    }
}
