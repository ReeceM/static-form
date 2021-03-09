<?php

namespace ReeceM\StaticForm\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use ReeceM\StaticForm\Actions\CreateStaticTokenAction;
use ReeceM\StaticForm\StaticForm;

class ManageStaticTokenController
{
    /**
     * Provides a status if the token exists
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(['version' => StaticForm::VERSION], 200);
    }

    /**
     * Creates a new token and returns the plain text instance.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function update()
    {
        $plainText = (new CreateStaticTokenAction)->create();

        return new JsonResponse([
            'plain_token' => $plainText,
            'message' => 'Token Created, please keep this as it is available once',
        ], 201);
    }
}
