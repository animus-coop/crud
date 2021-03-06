<?php

namespace AnimusCoop\CrudGenerator\Commands\Bases;

use AnimusCoop\CrudGenerator\Models\Resource;
use AnimusCoop\CrudGenerator\Support\Config;
use AnimusCoop\CrudGenerator\Traits\CommonCommand;
use Illuminate\Console\Command;

class ResourceFileCommandBase extends Command
{
    use CommonCommand;

    /**
     * Gets the resource from the given file
     *
     * @param string $file
     * @param array $languages
     *
     * @return AnimusCoop\CrudGenerator\Models\Resource
     */
    protected function getResources($file, array $languages = [])
    {
        return Resource::fromJson($this->getFileContent($file), 'animus', $languages);
    }

    /**
     * Gets the destenation filename.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getFilename($name)
    {
        $path = base_path(Config::getResourceFilePath());

        return $path . $name;
    }

    /**
     * Display a common error
     *
     * @return void
     */
    protected function noResourcesProvided()
    {
        $this->error('Nothing to append was provided! Please use the --fields, --relations, or --indexes to append to file.');
    }
}
