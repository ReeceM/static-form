<?php

namespace ReeceM\StaticForm\Actions;

use Illuminate\Support\Str;
use ReeceM\StaticForm\Contracts\StaticKeyStore;

class CreateStaticTokenAction
{
    public function create()
    {
        $token = hash('sha256', $textToken = Str::random(40));

        app(StaticKeyStore::class)
            ->put($token);

        return $textToken;
    }
}
