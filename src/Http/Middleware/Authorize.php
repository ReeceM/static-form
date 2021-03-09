<?php

namespace ReeceM\StaticForm\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        return Gate::check('manageFormTokens', [$request->user()])
            ? $next($request)
            : abort(403);
    }
}
