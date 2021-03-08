<?php

namespace ReeceM\StaticForm\Tests;

use Illuminate\Support\Facades\Storage;
use ReeceM\StaticForm\Actions\CreateStaticTokenAction;
use ReeceM\StaticForm\Contracts\StaticKeyStore;

class CoreTest extends TestCase
{
    /** @test */
    public function action_generates_token_to_file_storage()
    {
        Storage::fake();

        $plainToken = (new CreateStaticTokenAction)->create();

        $this->assertNotNull($plainToken);

        $this->assertNotNull(app(StaticKeyStore::class)->get(), "Static Key Store did not return value for get method");
        $this->assertTrue(hash_equals(
            app(StaticKeyStore::class)->get(),
            hash('sha256', $plainToken)
        ));

        Storage::assertExists(config('static-form.storage.path') . DIRECTORY_SEPARATOR . 'static.token');
    }
}
