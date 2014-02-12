<?php namespace Alba\Build\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

class BuildCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'build';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the application\'s assets with Gulp JS tasks.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Construct the Gulp JS command
		$gulp_command = base_path() . '/gulp build';
		if($this->argument('collection') != null)
		{
			$gulp_command .= ':' . $this->argument('collection');

			// Show watching...
			if($this->argument('collection') == 'watch')
			{
				$this->info('Builder is now watching for asset changes...');
			}

			// Show building...
			if($this->argument('collection') == 'styles')
			{
				$this->info('Builder is now building stylesheets...');
			}
			if($this->argument('collection') == 'scripts')
			{
				$this->info('Builder is now building scripts...');
			}

			// Show cleaning...
			if($this->argument('collection') == 'clean')
			{
				$this->info('Builder is now cleaning old asset builds...');
			}
		}

		// Execute the Gulp JS command
		$process = new Process($gulp_command);
		$process->run();

		// Catch error output
		if(!$process->isSuccessful())
		{
			throw new \RuntimeException($process->getErrorOutput());
		}

		// Print the Gulp JS output
		print $process->getOutput();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['collection', InputArgument::OPTIONAL, 'A collection to build.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
