<?php
namespace Jalno\Storage;

use \Illuminate\Filesystem\FilesystemAdapter;
use Jalno\Lumen\Contracts\{IStorage, IPackage};

class Repository implements IStorage
{
	protected IPackage $package;
	private array $disks = [];

	public function __construct(IPackage $package)
	{
		$this->package = $package;
	}

	public function public(): FilesystemAdapter
	{
		return $this->disk("public");
	}

	public function private(): FilesystemAdapter
	{
		return $this->disk("private");
	}

	public function disk(string $name): FilesystemAdapter
	{
		if (!isset($this->disks[$name])) {
			$this->disks[$name] = \Storage::createLocalDriver($this->package->getStorageConfig()[$name]);
		}

		return $this->disks[$name];
	}
}
