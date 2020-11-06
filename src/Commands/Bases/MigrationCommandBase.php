<?php

namespace AnimusCoop\CrudGenerator\Commands\Bases;

use AnimusCoop\CrudGenerator\Traits\Migration;
use AnimusCoop\CrudGenerator\Support\Helpers;
use Illuminate\Console\Command;

class MigrationCommandBase extends Command
{
    use Migration;

    /**
     * Create a of the migration command.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setMigrator();
    }
	
	protected function getMigratorNotes()
	{
		if(Helpers::isNewerThanOrEqualTo('5.7')) {
			return $this->migrator->setOutput($this->output);
		}
		
		return $this->migrator->getNotes();
	}
}
