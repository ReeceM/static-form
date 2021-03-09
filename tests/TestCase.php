<?php

namespace ReeceM\StaticForm\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use ReeceM\StaticForm\StaticFormServiceProvider;
use ReeceM\StaticForm\Tests\Models\User;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            function(string $modelName)  {
                return 'ReeceM\\StaticForm\\Database\\Factories\\'.class_basename($modelName).'Factory';
            }
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            StaticFormServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);

        if (!Schema::hasTable('users')) {
            include_once __DIR__.'/database/migrations/0000_00_00_000000_create_package_test_table.php';
            (new \CreatePackageTestTable())->up();
        }
    }
}
