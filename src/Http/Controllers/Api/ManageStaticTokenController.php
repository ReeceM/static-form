<?php

namespace ReeceM\StaticForm\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ReeceM\StaticForm\Contracts\StaticKeyStore;
use ReeceM\StaticForm\StaticForm;
use ReeceM\StaticForm\StaticFormFacade;

class ManageStaticTokenController
{
    /**
     * Provides a status if the token exists
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse([
            'version' => StaticForm::VERSION,
            'has_token' => app()->make(StaticKeyStore::class)->exists(),
        ], 200);
    }

    /**
     * Creates a new token and returns the plain text instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request)
    {
        $request->validate([
            'run_refresh' => 'required|accepted',
        ]);

        $plainText = StaticFormFacade::createToken();

        return new JsonResponse([
            'plain_token' => $plainText,
            'message' => 'Token Created, please keep this as it is available once',
        ], 201);
    }
}
