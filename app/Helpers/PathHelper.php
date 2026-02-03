<?php

namespace App\Helpers;

class PathHelper
{
    public static function publicRootPath(string $path = ''): string
    {
        $root = env('PUBLIC_PATH') ?: public_path();
        $root = rtrim($root, DIRECTORY_SEPARATOR);

        if ($path === '') {
            return $root;
        }

        return $root . DIRECTORY_SEPARATOR . ltrim($path, "/\\");
    }
}
