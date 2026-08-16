<?php

namespace App\Providers;


use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $files = File::files(app_path('Repositories/Eloquent'));

        foreach ($files as $file) {
            $class = $file->getFilenameWithoutExtension();

            if ($class === 'BaseRepository') {
                continue;
            }

            $interface = "App\\Repositories\\Contracts\\{$class}Interface";
            $repository = "App\\Repositories\\Eloquent\\{$class}";

            if (interface_exists($interface) && class_exists($repository)) {
                $this->app->bind($interface, $repository);
            }
        }
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
