<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use ReeceM\StaticForm\Actions\CreateStaticTokenAction;
use ReeceM\StaticForm\Http\Middleware\ValidStaticSiteKey;
use ReeceM\StaticForm\Tests\TestCase;

class ValidateTokenMiddlewareTest extends TestCase
{
    protected $token = null;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake();

        Route::middleware(ValidStaticSiteKey::class)->any('/_test/valid_token', function () {
            return new JsonResponse(['OK'], 200);
        });

        $this->afterApplicationCreated(function () {
            $this->token = (new CreateStaticTokenAction)->create();
        });
    }

    /** @test */
    public function the_middleware_validates_a_request_with_valid_token()
    {
        $this->withHeader('X-STATIC-FORM', $this->token)
            ->post('/_test/valid_token')
            ->assertOk()
            ->assertJson(['OK']);
    }

    /** @test */
    public function the_middleware_will_use_custom_config_value_for_key()
    {
        Config::set('static-form.header', 'x-static-api-key');

        $this->withHeader('x-static-api-key', $this->token)
            ->post('/_test/valid_token')
            ->assertOk()
            ->assertJson(['OK']);
    }

    /** @test */
    public function the_response_rejects_without_token()
    {
        $this->post('/_test/valid_token')
            ->assertStatus(422)
            ->assertSeeText('Missing');
    }

    /** @test */
    public function rejects_invalid_token_sent_to_middleware()
    {
        $this->withHeader('X-STATIC-FORM', 'random_string_that_is_40_characters_long')
            ->post('/_test/valid_token')
            ->assertStatus(422)
            ->assertJson(['error' => 'Invalid Token']);
    }
}
