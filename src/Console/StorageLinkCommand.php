<?php

namespace Jalno\Storage\Console;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @property string $signature
     */
    protected $signature = 'storage:link
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';

    /**
     * The console command description.
     *
     * @property string $description
     */
    protected $description = 'Create the symbolic links configured for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $relative = $this->option('relative');

        foreach ($this->links() as $link => $target) {
            $link = strval($link);
            $force = boolval($this->option('force'));
            if (file_exists($link) && !$this->isRemovableSymlink($link, $force)) {
                $this->error("The [$link] link already exists.");
                continue;
            }

            if (is_link($link)) {
                app()->make('files')->delete($link);
            }

            if ($relative) {
                app()->make('files')->relativeLink($target, $link);
            } else {
                app()->make('files')->link($target, $link);
            }

            $this->info("The [$link] link has been connected to [$target].");
        }

        $this->info('The links have been created.');
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array<string, string>
     */
    protected function links()
    {
        return app()['config']['filesystems.links'] ??
               [app()->basePath('public/storage') => storage_path('public')];
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     *
     * @param  string  $link
     * @param  bool  $force
     * @return bool
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
