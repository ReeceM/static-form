<?php

namespace ReeceM\StaticForm;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string createToken()
 * @method static \ReeceM\StaticForm\StaticForm handle(string $form, \Illuminate\Http\Request $request)
 * @method static \ReeceM\StaticForm\StaticForm define(string $form, \Closure $callback)
 * @see \ReeceM\StaticForm\StaticForm
 */
class StaticFormFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'static-form';
    }
}
