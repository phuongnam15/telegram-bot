<?php

namespace App\Services\_Trait;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait SaveFile
{
    public function saveImage($file, $path = "", $source = "")
    {
        if (!File::isDirectory(public_path('storage/' . $path))) {
            File::makeDirectory(public_path('storage/' . $path), 0777, true, true);
        }
        $fileName = Str::random(4) . '_' . preg_replace('/\s+/', '', $file->getClientOriginalName());
        Storage::putFileAs($source, $file, $fileName);

        return $fileName;
    }

    function saveMultiImages($files, $path = "", $source = "")
    {
        if (!File::isDirectory(public_path('storage/' . $path))) {
            File::makeDirectory(public_path('storage/' . $path), 0777, true, true);
        }

        $fileName = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $fileName[] = $this->saveImage($file, $path, $source);
            }
        }

        return $fileName;
    }

    function getImage($file, $path = "", $source = "")
    {
        $url = asset(Storage::url($path . '/' . $file));

        return $url;
    }

    function getImages($files, $path = "", $source = "")
    {
        $url = [];

        foreach ($files as $file) {
            $url[] = $this->getImage($file, $path, $source);
        }

        return $url;
    }
}
