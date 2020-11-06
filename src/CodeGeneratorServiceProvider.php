<?php

namespace Animus\CodeGenerator;

use Animus\CodeGenerator\Support\Helpers;
use File;
use Illuminate\Support\ServiceProvider;

class CodeGeneratorServiceProvider extends ServiceProvider
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

        // publish the default-template
        $this->publishes([
            $dir . 'templates/default' => $this->codeGeneratorBase('templates/default'),
        ], 'default-template');

        // publish the defaultcollective-template
        $this->publishes([
            $dir . 'templates/default-collective' => $this->codeGeneratorBase('templates/default-collective'),
        ], 'default-collective-template');
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
            'Animus\CodeGenerator\Commands\Framework\CreateControllerCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateModelCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateLanguageCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateFormRequestCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateRoutesCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateMigrationCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateScaffoldCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateResourcesCommand',
            'Animus\CodeGenerator\Commands\Framework\CreateMappedResourcesCommand',
            'Animus\CodeGenerator\Commands\Resources\ResourceFileFromDatabaseCommand',
            'Animus\CodeGenerator\Commands\Resources\ResourceFileCreateCommand',
            'Animus\CodeGenerator\Commands\Resources\ResourceFileDeleteCommand',
            'Animus\CodeGenerator\Commands\Resources\ResourceFileAppendCommand',
            'Animus\CodeGenerator\Commands\Resources\ResourceFileReduceCommand',
            'Animus\CodeGenerator\Commands\Views\CreateIndexViewCommand',
            'Animus\CodeGenerator\Commands\Views\CreateCreateViewCommand',
            'Animus\CodeGenerator\Commands\Views\CreateFormViewCommand',
            'Animus\CodeGenerator\Commands\Views\CreateEditViewCommand',
            'Animus\CodeGenerator\Commands\Views\CreateShowViewCommand',
            'Animus\CodeGenerator\Commands\Views\CreateViewsCommand',
            'Animus\CodeGenerator\Commands\Views\CreateViewLayoutCommand',
            'Animus\CodeGenerator\Commands\Views\CreateLayoutCommand',
            'Animus\CodeGenerator\Commands\Api\CreateApiControllerCommand',
            'Animus\CodeGenerator\Commands\Api\CreateApiScaffoldCommand',
            'Animus\CodeGenerator\Commands\ApiDocs\CreateApiDocsControllerCommand',
            'Animus\CodeGenerator\Commands\ApiDocs\CreateApiDocsScaffoldCommand',
            'Animus\CodeGenerator\Commands\ApiDocs\CreateApiDocsViewCommand',
                'Animus\CodeGenerator\Commands\Resources\ResourceFileFromDatabaseAllCommand'
        ];

        if (Helpers::isNewerThanOrEqualTo()) {
            $commands = array_merge($commands,
                [
                    'Animus\CodeGenerator\Commands\Migrations\MigrateAllCommand',
                    'Animus\CodeGenerator\Commands\Migrations\RefreshAllCommand',
                    'Animus\CodeGenerator\Commands\Migrations\ResetAllCommand',
                    'Animus\CodeGenerator\Commands\Migrations\RollbackAllCommand',
                    'Animus\CodeGenerator\Commands\Migrations\StatusAllCommand',
                ]);
        }

        if (Helpers::isApiResourceSupported()) {
            $commands = array_merge($commands,
                [
                    'Animus\CodeGenerator\Commands\Api\CreateApiResourceCommand',
                ]);
        }

        $this->commands($commands);
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
