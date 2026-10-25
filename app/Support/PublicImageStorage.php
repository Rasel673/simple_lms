<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class PublicImageStorage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $directoryPath = public_path($directory);
        File::ensureDirectoryExists($directoryPath);

        $filename = $file->hashName();
        $file->move($directoryPath, $filename);

        return $directory . '/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
