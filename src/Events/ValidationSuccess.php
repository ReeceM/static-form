<?php

namespace ReeceM\StaticForm\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ValidationSuccess
{
    use Dispatchable;

    /**
     * The incoming payload.
     *
     * @var array
     */
    public $payload;

    /**
     * Create a new event instance.
     *
     * @param  array  $payload
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
