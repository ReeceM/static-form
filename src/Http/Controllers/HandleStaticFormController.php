<?php

namespace ReeceM\StaticForm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReeceM\StaticForm\StaticFormFacade;

class HandleStaticFormController
{
    public function __invoke(Request $request, string $form)
    {
        /**
         * @todo Implement the definable forms and handler.
         * Just return a notice if someone tries to use the endpoint
         */
        return new JsonResponse(['error' => 'Feature Not Implemented'], Response::HTTP_NOT_IMPLEMENTED);

        $response = StaticFormFacade::handle($form, $request);

        return is_null($response)
            ? new JsonResponse(['message' => 'accepted'], 200)
            : $response;
    }
}
