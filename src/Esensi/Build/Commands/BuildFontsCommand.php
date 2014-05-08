<?php namespace Esensi\Build\Commands;

use \Esensi\Build\Commands\BuildCommand;

class BuildFontsCommand extends BuildCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'build:fonts';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Builds the application\'s font assets.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('build', ['task' => 'fonts']);
	}

	/**
	 * Get the console command arguments.
	 *
	 * This is stubbed to overwrite parent class.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

}
