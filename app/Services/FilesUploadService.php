<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FilesUploadService
{
    public function uploadFile($file, $path)
    {
        $fileName = Storage::disk('public')->put($path, $file);
        return $fileName;
    }
}
