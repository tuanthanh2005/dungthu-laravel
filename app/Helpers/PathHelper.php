<?php

namespace App\Helpers;

class PathHelper
{
    public static function publicRootPath(string $path = ''): string
    {
        $preferred = config('filesystems.disks.public_uploads.root') ?: base_path('../public_html');
        $root = is_dir($preferred) ? $preferred : public_path();
        $root = rtrim($root, DIRECTORY_SEPARATOR);

        if ($path === '') {
            return $root;
        }

        return $root . DIRECTORY_SEPARATOR . ltrim($path, "/\\");
    }
}
