<?php

namespace ReeceM\StaticForm;

use Closure;
use Illuminate\Http\Request;
use ReeceM\StaticForm\Actions\CreateStaticTokenAction;
use ReeceM\StaticForm\Events\SubmissionAccepted;

class StaticForm
{
    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '0.2.x';

    /**
     * Create custom handlers for the forms in the url.
     *
     * @var (Closure)[]
     */
    public $customHandlers = [];

    /**
     * Creates a new token using the action.
     *
     * @return string
     */
    public function createToken()
    {
        return (new CreateStaticTokenAction)->create();
    }

    public function handle($form, Request $request)
    {
        if (! isset($this->customHandlers[$form])) {
            SubmissionAccepted::dispatch($request->all());

            return null;
        }

        $callback = $this->customHandlers[$form];

        return $callback($request);
    }

    /**
     * Creates a definition to handle incoming forms.
     *
     * @param string $form
     * @param  \Closure  $callback
     * @return \ReeceM\StaticForm\StaticForm
     */
    public function define(string $form, Closure $callback)
    {
        $this->customHandlers[$form] = $callback;

        return $this;
    }
}
