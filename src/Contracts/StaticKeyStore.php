<?php

namespace ReeceM\StaticForm\Contracts;

interface StaticKeyStore
{
    /**
     * Get the hashed key in storage.
     *
     * @return string
     */
    public function get(): string;

    /**
     * Puts the static site key hash into storage
     *
     * @param string $hash
     * @return bool
     */
    public function put(string $hash): bool;

    /**
     * Determine if the token has been stored on media.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Removes the hash from the storage completely.
     *
     * @return bool
     */
    public function destroy(): bool;
}
