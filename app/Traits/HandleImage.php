<?php

namespace App\Traits;

use App\Helpers\PathHelper;
use Illuminate\Support\Str;

trait HandleImage
{
    /**
     * Crop và resize ảnh về kích thước chuẩn.
     */
    protected function cropImage($file, $width = 500, $height = 334)
    {
        $image = @imagecreatefromstring(file_get_contents($file));
        if (!$image) return null;

        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);
        
        $targetRatio = $width / $height;
        $srcRatio = $srcWidth / $srcHeight;
        
        if ($srcRatio > $targetRatio) {
            $cropHeight = $srcHeight;
            $cropWidth = $srcHeight * $targetRatio;
            $srcX = ($srcWidth - $cropWidth) / 2;
            $srcY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = $srcWidth / $targetRatio;
            $srcX = 0;
            $srcY = ($srcHeight - $cropHeight) / 2;
        }
        
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        
        imagecopyresampled(
            $newImage, $image,
            0, 0, $srcX, $srcY,
            $width, $height, $cropWidth, $cropHeight
        );
        
        return $newImage;
    }

    /**
     * Resize ảnh giữ tỷ lệ (không crop).
     */
    protected function resizeImage($file, $maxWidth = 1200, $maxHeight = 1200)
    {
        $image = @imagecreatefromstring(file_get_contents($file));
        if (!$image) return null;

        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);
        
        $ratio = min($maxWidth / $srcWidth, $maxHeight / $srcHeight, 1);
        $width = (int)($srcWidth * $ratio);
        $height = (int)($srcHeight * $ratio);
        
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        
        imagecopyresampled(
            $newImage, $image,
            0, 0, 0, 0,
            $width, $height, $srcWidth, $srcHeight
        );
        
        return $newImage;
    }
    
    /**
     * Lưu ảnh và trả về đường dẫn URL (asset full path).
     */
    protected function saveImage($image, $path, $extension)
    {
        if (!$image) return null;

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        switch(strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $path, 90);
                break;
            case 'png':
                imagepng($image, $path, 8);
                break;
            case 'gif':
                imagegif($image, $path);
                break;
            case 'webp':
                if (function_exists('imagewebp')) {
                    imagewebp($image, $path, 85);
                } else {
                    imagejpeg($image, $path, 90);
                }
                break;
            default:
                imagejpeg($image, $path, 90);
        }
        imagedestroy($image);
    }

    /**
     * Helper tổng hợp để lưu ảnh theo chuẩn (như Blog post).
     * Mặc định lưu vào images/{folder} và trả về asset URL.
     */
    protected function uploadStandardImage($file, $folder = 'blogs', $width = 1200, $height = 800, $crop = true)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . Str::random(10) . '.' . $extension;
        $relativeDir = 'images/' . $folder;
        $fullPath = PathHelper::publicRootPath($relativeDir . '/' . $fileName);
        
        if ($crop) {
            $processed = $this->cropImage($file, $width, $height);
        } else {
            $processed = $this->resizeImage($file, $width, $height);
        }

        if ($processed) {
            $this->saveImage($processed, $fullPath, $extension);
            return asset($relativeDir . '/' . $fileName);
        }

        // Fallback: move file gốc nếu không xử lý được
        $file->move(dirname($fullPath), basename($fullPath));
        return asset($relativeDir . '/' . $fileName);
    }
}
