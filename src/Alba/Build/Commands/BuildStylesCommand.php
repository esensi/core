<?php namespace Alba\Build\Commands;

use Alba\Build\Commands\BuildCommand;

class BuildStylesCommand extends BuildCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'build:styles';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Builds the application\'s stylesheet assets.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('build', ['collection' => 'styles']);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

}
