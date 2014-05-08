<?php namespace Esensi\Build\Commands;

use \RuntimeException;
use \Illuminate\Console\Command;
use \Illuminate\Support\Facades\App;
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
		
		$task = $this->argument('task');
		if($task != null)
		{
			$gulp_command .= ':' . $task;

			// Show subtask process
			switch($task)
			{
				case 'watch':
					$this->info('Builder is now watching for asset changes...');
					break;

				case 'clean':
					$this->info('Builder is now cleaning old asset builds...');
					break;

				case 'lint':
					$this->info('Builder is now linting assets...');
					break;

				case 'styles':
					$this->info('Builder is now building stylesheets...');
					break;

				case 'scripts':
					$this->info('Builder is now building scripts...');
					break;

				case 'images':
					$this->info('Builder is now building images...');
					break;

				case 'fonts':
					$this->info('Builder is now building fonts...');
					break;
			}
		}

		// Compare environment to configured environments for production
		$is_production = in_array(App::environment(), Config::get('esensi::build.environments', ['production']));

		// Run in production mode
		if($is_production || $this->option('production') === true)
		{
			$gulp_command .= ' --production';
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
			['task', InputArgument::OPTIONAL, 'A task to run.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{	
		return [
			['production', 'p', InputOption::VALUE_NONE, 'Optimizes the build for production'],
		];
	}

}
