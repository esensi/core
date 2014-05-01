<?php namespace \Esensi\Build\Commands;

use \RuntimeException;
use \Illuminate\Console\Command;
use \Illuminate\Support\Facades\Config;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Process\Process;
use \Symfony\Component\Process\Exception\ProcessTimedOutException;

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
	 * @see http://symfony.com/blog/new-in-symfony-2-2-process-component-enhancements
	 * @return mixed
	 */
	public function fire()
	{
		// Construct the Gulp JS command
		$gulp_path = Config::get('esensi::build.binary', base_path() . '/node_modules/.bin/gulp');
		$gulp_command = $gulp_path . ' build';
		
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

		// Add the gulp command to the proccesses
		$processes = [
			new Process($gulp_command)
		];

		// Keep the processes running without timeout
		while( count($processes) > 0 )
		{
			foreach($processes as $i => $process)
			{
				// Start processes that haven't started yet
				if(!$process->isStarted())
				{
					$process->start();
					continue;
				}

				// Show incremental output
				echo $process->getIncrementalOutput();

				// Show incremental error output with highlighting
				$output = $process->getIncrementalErrorOutput();
				if(!empty($output))
				{
					$this->error(rtrim($output, "\n"));
				}
				
				// Remove the process once it's stopped running
				if(!$process->isRunning())
				{
					// Show success when it completes
					if($process->isSuccessful())
					{
						$this->info('Builder has finished running "' . $gulp_command . '".');
					}

					// Show error when it errors out
					else
					{
						$this->error('Builder encountered an error while running "' . $gulp_command . '".');
					}

					// Remove the process from those running
					unset($processes[$i]);
				}
			}
		}
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
