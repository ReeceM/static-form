<?php

namespace ReeceM\StaticForm\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReeceM\StaticForm\Contracts\StaticKeyStore;
use ReeceM\StaticForm\Events\ValidationFailed;
use ReeceM\StaticForm\Events\ValidationSuccess;

class ValidStaticSiteKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->hasHeader(config('static-form.header'))) {

            ValidationFailed::dispatch(['request' => $request->all()]);

            return $request->expectsJson()
                ? response()->json(['error' => 'Missing Token'], 422)
                : response('Missing Token', 422);
        }

        return $this->validToken($request)
            ? $next($request)
            : response()->json(['error' => 'Invalid Token'], 422);
    }

    /**
     * Validates the token against the stored hash.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     * @throws BindingResolutionException
     */
    private function validToken($request)
    {
        $token = app(StaticKeyStore::class)->get();

        $header = $request->header(config('static-form.header'));

        return tap(
            hash_equals($token, hash('sha256', $header)),
            function ($result) use ($request) {
                $payload = [
                    'result' => $result,
                    'request' => $request,
                ];

                $result
                    ? ValidationSuccess::dispatch($payload)
                    : ValidationFailed::dispatch($payload);
            }
        );
    }
}
