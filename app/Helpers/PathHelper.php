<?php

namespace App\Helpers;

class PathHelper
{
    public static function publicRootPath(string $path = ''): string
    {
        $preferred = config('filesystems.disks.public_uploads.root') ?: base_path('../public_html');
        $root = public_path();

        if (is_dir($preferred)) {
            $host = null;
            if (!app()->runningInConsole() && request()) {
                $host = request()->getHost();
            }

            // Use public_html on non-localhost; keep local dev on /public to avoid broken assets.
            if (!$host || !in_array($host, ['localhost', '127.0.0.1'], true)) {
                $root = $preferred;
            }
        }
        $root = rtrim($root, DIRECTORY_SEPARATOR);

        if ($path === '') {
            return $root;
        }

        return $root . DIRECTORY_SEPARATOR . ltrim($path, "/\\");
    }
}
