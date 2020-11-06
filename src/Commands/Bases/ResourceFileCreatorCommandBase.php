<?php

namespace AnimusCoop\CrudGenerator\Commands\Bases;

use AnimusCoop\CrudGenerator\Commands\Bases\ResourceFileCommandBase;
use AnimusCoop\CrudGenerator\Support\Arr;
use AnimusCoop\CrudGenerator\Support\Str;
use AnimusCoop\CrudGenerator\Support\Helpers;

class ResourceFileCreatorCommandBase extends ResourceFileCommandBase
{
    /**
     * Converts the current command's argument and options into an array.
     *
     * @return array
     */
    protected function getCommandOptions($input)
    {
        return [
            'model-name' => $this->argument('model-name'),
            '--resource-filename' => $this->option('resource-filename'),
            '--fields' => $this->option('fields'),
            '--translation-for' => $this->option('translation-for'),
            '--relations' => $this->option('relations'),
            '--indexes' => $this->option('indexes'),
        ];
    }

    /**
     * Gets a clean user inputs.
     *
     * @return object
     */
    protected function getCommandInput()
    {
        $modelName = ucfirst(\AnimusCoop\CrudGenerator\Support\Str::camel(trim($this->argument('model-name'))));
        $filename = trim($this->option('resource-filename'));
        $file = $filename ? Str::finish($filename, '.json') : Helpers::makeJsonFileName($modelName);
        $translationFor = array_unique(Arr::fromString($this->option('translation-for')));
        $fieldNames = array_unique(Arr::fromString($this->option('fields')));
        $relations = Arr::fromString($this->option('relations'));
        $indexes = Arr::fromString($this->option('indexes'));

        return (object) compact(
            'modelName',
            'file',
            'fieldNames',
            'indexes',
            'relations',
            'translationFor'
        );
    }

}
