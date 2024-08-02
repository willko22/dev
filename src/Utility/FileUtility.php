<?php
namespace App\Utility;

use Laminas\Diactoros\UploadedFile;

class FileUtility
{
    public static function deleteFile(string $filePath) : bool
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public static function saveFile(UploadedFile $file, string $destination) : ?string
    {
        $file->moveTo($destination . $file->getClientFilename());
        return $file->getClientFilename();
    }
}