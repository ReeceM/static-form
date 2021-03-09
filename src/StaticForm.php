<?php

namespace ReeceM\StaticForm;

use Closure;
use Illuminate\Http\Request;
use ReeceM\StaticForm\Events\SubmissionAccepted;

class StaticForm
{
    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '0.1.x';

    public $customHandlers = [];

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
