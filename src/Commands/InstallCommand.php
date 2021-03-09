<?php

namespace ReeceM\StaticForm\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'static-form:install {
            --provider : Publish the provider for the package
            --config : Publishes the config for the package
        }';

    public $description = 'Installs the package for the application';

    public function handle()
    {
        $result = 0;

        if ($this->option('provider')) {
            $result = $this->call('vendor:publish', [
                '--provider' => "ReeceM\StaticForm\StaticFormServiceProvider",
                '--tag' => 'static-form-provider',
            ]);
        }

        if ($this->option('config')) {
            $result = $this->call('vendor:publish', [
                '--provider' => "ReeceM\StaticForm\StaticFormServiceProvider",
                '--tag' => 'static-form-config',
            ]);
        }

        $result
            ? $this->info('Installation commands run')
            : $this->warn('Not Installed');

        return 1;
    }
}
