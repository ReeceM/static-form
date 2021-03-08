<?php

namespace ReeceM\StaticForm\Commands;

use Illuminate\Console\Command;
use ReeceM\StaticForm\Actions\CreateStaticTokenAction;

class StaticFormCommand extends Command
{
    public $signature = 'static-form {--refresh : Refreshes the current token}';

    public $description = 'Handles regenerating a new static form token';

    public function handle()
    {
        if ($this->option('refresh')) {
            $confirm = $this->confirm('Are you should you would like to do this?', false);

            $result = $confirm ? (new CreateStaticTokenAction)->create() : false;

            $result
                ? $this->info(sprintf('This is your token (you will only see this now): %s', $result))
                : $this->comment('No token refreshed');
        }

        $this->info($this->help);

        return 1;
    }
}
