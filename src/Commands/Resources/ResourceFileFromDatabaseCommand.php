<?php

namespace Animus\CodeGenerator\Commands\Resources;

use Animus\CodeGenerator\Commands\Bases\ResourceFileCommandBase;
use Animus\CodeGenerator\Models\Field;
use Animus\CodeGenerator\Models\MigrationCapsule;
use Animus\CodeGenerator\Models\MigrationTrackerCapsule;
use Animus\CodeGenerator\Models\Resource;
use Animus\CodeGenerator\Support\Arr;
use Animus\CodeGenerator\Support\Str;
use Animus\CodeGenerator\Support\Config;
use Animus\CodeGenerator\Support\Helpers;
use Animus\CodeGenerator\Support\MigrationHistoryTracker;
use Animus\CodeGenerator\Support\ResourceMapper;
use Animus\CodeGenerator\Traits\LanguageTrait;
use Animus\CodeGenerator\Traits\Migration;
use DB;
use Exception;
use File;
use Animus\CodeGenerator\Support\Str;

class ResourceFileFromDatabaseCommand extends ResourceFileCommandBase
{
    use Migration, LanguageTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource-file:from-database
                            {model-name : The model name that these files represent.}
                            {--table-name= : The database table name to fetch the field from.}
                            {--database-name= : The database name the table is stored in.}
                            {--resource-filename= : The destination file name to create.}
                            {--translation-for= : A comma seperated string of languages to create fields for.}
                            {--force : This option will override the view if one already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a resource-file from existing table in a database.';

    /**
     * The supported database drivers. lowercase only
     *
     * @var array
     */
    protected $drivers = ['mysql'];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->generate();
    }
    public function generate()
    {
        $file = $this->getDestinationFullname();

        if ($this->alreadyExists($file)) {
            $this->error('The resource-file already exists! To override the existing file, use --force option.');

            return false;
        }

        $parser = $this->getParser();
        $tableName = $this->getTableName();

        if (Config::autoManageResourceMapper()) {
            $mapper = new ResourceMapper($this);
            $mapper->append($this->getModelName(), $this->getNewFilename(), $tableName);
        }

        $resource = $parser->getResource();
        $modelName = $this->getModelName();
        $resource->setDefaultApiDocLabels($modelName, self::makeLocaleGroup($modelName), $this->getLanguages());

        $this->createVirtualMigration($resource, $file, $tableName);

        $this->createFile($file, Helpers::prettifyJson($resource->toArray()))
            ->info('The "' . basename($file) . '" file was crafted successfully!');
    }

    /**
     * Gets the full name of the destination file.
     *
     * @return string
     */
    protected function getDestinationFullname()
    {
        return base_path(Config::getResourceFilePath($this->getNewFilename()));
    }

    /**
     * Creates a virtual migration in the migration tracker
     *
     * @param Animus\CodeGenerator\Models\Resource $resource
     * @param string $destenationFile
     * @param string $tableName
     *
     * @return void
     */
    protected function createVirtualMigration(Resource $resource, $destenationFile, $tableName)
    {
        $tracker = new MigrationHistoryTracker();
        $capsule = $tracker->get($tableName);

        if (!is_null($capsule)) {
            $tracker->forget($tableName);
        }
        // At this point there are no capsule or migration associated with this table.
        $capsule = MigrationTrackerCapsule::get($tableName, $this->getModelName(), Helpers::convertSlashToBackslash($destenationFile));
        $migration = $this->getMigrationCapsule($resource, $tableName);
        $tracker->add($capsule, $migration);
    }

    /**
     * Gets the fields' collection after from using the connection's driver.
     *
     * @return array
     */
    protected function getParser()
    {
        $driver = strtolower(DB::getDriverName());

        if (!in_array($driver, $this->drivers)) {
            throw new Exception('The database driver user is not supported!');
        }

        $class = sprintf('Animus\CodeGenerator\DatabaseParsers\%sParser', ucfirst($driver));

        return new $class($this->getTableName(), $this->getDatabaseName(), $this->getLanguages());
    }

    /**
     * Checks the options to see if the force command was provided.
     *
     * @return bool
     */
    protected function isForce()
    {
        return $this->option('force');
    }

    /**
     * Gets the destenation filename.
     *
     * @return string
     */
    protected function getNewFilename()
    {
        $filename = trim($this->option('resource-filename')) ?: Helpers::makeJsonFileName($this->getModelName());

        return Str::finish($filename, '.json');
    }

    /**
     * Gets the model name.
     *
     * @return string
     */
    protected function getModelName()
    {
        return trim($this->argument('model-name'));
    }

    /**
     * Gets the database name.
     *
     * @return string
     */
    protected function getDatabaseName()
    {
        return trim($this->option('database-name')) ?: DB::getConfig('database');
    }

    /**
     * Gets the table name.
     *
     * @return string
     */
    protected function getTableName()
    {
        return trim($this->option('table-name')) ?: Helpers::makeTableName($this->getModelName());
    }

    /**
     * Gets the languages to create lang keys for.
     *
     * @return array
     */
    protected function getLanguages()
    {
        return Arr::fromString($this->option('translation-for'));
    }

    /**
     * Gets migration fullname
     *
     * @param string $name
     *
     * @return string
     */
    protected function getMigrationFullName($name)
    {
        $folder = '';

        if (Config::organizeMigrations()) {
            $folder = $this->getTableName();
        }

        return $this->getMigrationPath($folder) . DIRECTORY_SEPARATOR . $name . '.php';
    }

    /**
     * Make a migration capsule
     *
     * @param Animus\CodeGenerator\Models\Resource $resource
     * @param string $tableName
     *
     * @return Animus\CodeGenerator\Models\MigrationCapsule
     */
    protected function getMigrationCapsule($resource, $tableName)
    {
        $migration = MigrationCapsule::get($this->getCreateMigrationName($tableName));

        $migration->path = $this->getMigrationFullName($migration->name);
        $migration->resource = $resource;
        $migration->className = $this->makeCreateTableClassName(Helpers::makeTableName($tableName));
        $migration->isCreate = true;
        $migration->isVirtual = true;
        $migration->withSoftDelete = $this->hasSoftDelete($resource->fields);
        $migration->withoutTimestamps = !$this->hasTimeStamps($resource->fields);

        return $migration;
    }

    /**
     * Checks if the resource contains a soft delete
     *
     * @param array $fields
     *
     * @return bool
     */
    protected function hasSoftDelete($fields)
    {
        foreach ($fields as $field) {
            if ($field->isAutoManagedOnDelete()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the resource contains a time stamps
     *
     * @param array $fields
     *
     * @return bool
     */
    protected function hasTimeStamps($fields)
    {
        foreach ($fields as $field) {
            if ($field->isAutoManagedOnUpdate()) {
                return true;
            }
        }

        return false;
    }
}
