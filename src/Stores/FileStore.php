<?php

namespace ReeceM\StaticForm\Stores;

use Illuminate\Contracts\Filesystem\Filesystem;
use ReeceM\StaticForm\Contracts\StaticKeyStore;

class FileStore implements StaticKeyStore
{
    protected $filesystem;

    /**
     * Create a new local storage instance.
     *
     * @param \Illuminate\Contracts\Filesystem\ $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Get the hashed key in storage.
     *
     * @return string
     */
    public function get(): string
    {
        return rescue(function () {
            $token = $this->filesystem->get($this->path());

            $token = json_decode($token, true);

            return $token['token'];
        });
    }

    /**
     * Sets the static site key hash
     *
     * @param string $hash
     * @return void
     */
    public function put(string $hash): bool
    {
        return $this->filesystem->put($this->path(), json_encode([
            'token' => $hash,
        ]));
    }

    /**
     * Removes the hash from the storage completely.
     *
     * @return bool
     */
    public function destroy(): bool
    {
        return $this->filesystem->delete($this->path());
    }

    public function path()
    {
        return config('static-form.storage.path') . DIRECTORY_SEPARATOR . 'static.token';
    }
}
