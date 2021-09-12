<?php

namespace AnimusCoop\CrudGenerator;

use AnimusCoop\CrudGenerator\Support\Helpers;
use File;
use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $dir = __DIR__ . '/../';

        // publish the config base file
        $this->publishes([
            $dir . 'config/animus-crud-generator.php' => config_path('animus-crud-generator.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/assets' => public_path('cms'),
        ], 'public');

//        $this->loadViewsFrom(__DIR__.'/Views', 'crud');
        
         $this->publishes([
             __DIR__.'/Views' => resource_path('views/vendor/crud-generator'),
         ]);

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $commands =
            [
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateControllerCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateModelCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateLanguageCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateFormRequestCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateRoutesCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateMigrationCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateScaffoldCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateResourcesCommand',
            'AnimusCoop\CrudGenerator\Commands\Framework\CreateMappedResourcesCommand',
            'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileFromDatabaseCommand',
            'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileCreateCommand',
            'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileDeleteCommand',
            'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileAppendCommand',
            'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileReduceCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateIndexViewCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateCreateViewCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateFormViewCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateEditViewCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateShowViewCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateViewsCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateViewLayoutCommand',
            'AnimusCoop\CrudGenerator\Commands\Views\CreateLayoutCommand',
            'AnimusCoop\CrudGenerator\Commands\Api\CreateApiControllerCommand',
            'AnimusCoop\CrudGenerator\Commands\Api\CreateApiScaffoldCommand',
            'AnimusCoop\CrudGenerator\Commands\ApiDocs\CreateApiDocsControllerCommand',
            'AnimusCoop\CrudGenerator\Commands\ApiDocs\CreateApiDocsScaffoldCommand',
            'AnimusCoop\CrudGenerator\Commands\ApiDocs\CreateApiDocsViewCommand',
                'AnimusCoop\CrudGenerator\Commands\Resources\ResourceFileFromDatabaseAllCommand'
        ];

        if (Helpers::isNewerThanOrEqualTo()) {
            $commands = array_merge($commands,
                [
                    'AnimusCoop\CrudGenerator\Commands\Migrations\MigrateAllCommand',
                    'AnimusCoop\CrudGenerator\Commands\Migrations\RefreshAllCommand',
                    'AnimusCoop\CrudGenerator\Commands\Migrations\ResetAllCommand',
                    'AnimusCoop\CrudGenerator\Commands\Migrations\RollbackAllCommand',
                    'AnimusCoop\CrudGenerator\Commands\Migrations\StatusAllCommand',
                ]);
        }

        if (Helpers::isApiResourceSupported()) {
            $commands = array_merge($commands,
                [
                    'AnimusCoop\CrudGenerator\Commands\Api\CreateApiResourceCommand',
                ]);
        }

        $this->commands($commands);

        $this->loadViewsFrom(__DIR__.'/views', null);
    }

    /**
     * Create a directory if one does not already exists
     *
     * @param string $path
     *
     * @return void
     */
    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
        }
    }

    /**
     * Get the animus-crud-generator base path
     *
     * @param string $path
     *
     * @return string
     */
    protected function codeGeneratorBase($path = null)
    {
        return base_path('resources/animus-crud-generator/') . $path;
    }
}
