<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use ReeceM\StaticForm\StaticForm;
use ReeceM\StaticForm\Tests\Models\User;
use ReeceM\StaticForm\Tests\TestCase;

class ManageTokenApiControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Storage::fake();

        User::create([
            'email' => 'reece@reecemay.me',
            'password' => Hash::make('password'),
        ]);

        Gate::define('manageFormTokens', function ($user) {
            return in_array($user->email, [
                'reece@reecemay.me',
            ]);
        });
    }

    /** @test */
    public function token_status_can_be_checked_when_logged_in()
    {
        $this->actingAs(User::first())
            ->get(route('static-form.token.index'))
            ->assertOk()
            ->assertJson(['version' => StaticForm::VERSION]);
    }

    /** @test */
    public function token_status_cannot_be_viewed_when_unauthed()
    {
        $this->get(route('static-form.token.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function token_can_be_created_when_logged_in()
    {
        $this->actingAs(User::first())
            ->patch(route('static-form.token.update'))
            ->assertCreated()
            ->assertJsonStructure(['plain_token', 'message']);
    }

    /** @test */
    public function token_cannot_created_when_not_logged_in()
    {
        $this->post(route('static-form.token.update'))
            ->assertStatus(403);
    }
}
