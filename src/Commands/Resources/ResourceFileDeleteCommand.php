<?php

namespace Animus\CodeGenerator\Commands\Resources;

use Animus\CodeGenerator\Commands\Bases\ResourceFileCommandBase;
use Animus\CodeGenerator\Support\Config;
use Animus\CodeGenerator\Support\Helpers;
use Animus\CodeGenerator\Support\Str;
use Animus\CodeGenerator\Support\ResourceMapper;

class ResourceFileDeleteCommand extends ResourceFileCommandBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource-file:delete
                            {model-name : The model name that these files represent.}
                            {--resource-filename= : The destination file name to delete.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing resource-file.';

    /**
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $input = $this->getCommandInput();
        $file = $this->getFilename($input->file);

        if (Config::autoManageResourceMapper()) {
            $mapper = new ResourceMapper($this);
            $mapper->reduce($input->modelName, $input->file);
        }

        if (!$this->isFileExists($file)) {
            $this->error('The resource-file does not exists.');

            return false;
        }

        $this->deleteFile($file);
        $this->info('The "' . basename($file) . '" file was successfully deleted!');
    }

    /**
     * Gets a clean user inputs.
     *
     * @return object
     */
    protected function getCommandInput()
    {
        $modelName = trim($this->argument('model-name'));
        $filename = trim($this->option('resource-filename'));
        $file = $filename ? Str::finish($filename, '.json') : Helpers::makeJsonFileName($modelName);

        return (object) compact('modelName', 'file');
    }
}
