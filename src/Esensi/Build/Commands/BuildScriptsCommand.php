<?php namespace Esensi\Build\Commands;

use \Esensi\Build\Commands\BuildCommand;

class BuildScriptsCommand extends BuildCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:scripts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the application\'s script assets.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('build', ['task' => 'scripts']);
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
