<?php

namespace App\Services\_Trait;

use Illuminate\Support\Str;

trait SaveFileTrait
{
    public function saveFile($data, $attribute)
    {
        if (isset($data[$attribute])) {
            $file = $data[$attribute];
            $filename = Str::random(4) . '_' . preg_replace('/\s+/', '', $file->getClientOriginalName());
            while (file_exists(config('common.source_image') . $filename)) {
                $filename = Str::random(4) . '_' . $filename;
            }
            $file->move(config('common.source_image'), $filename);
            $directory = asset(config('common.source_image'));
            $data[$attribute] = $directory.'/'.$filename;
        }

        return $data;
    }

    /**
     * @param $files
     * @return array
     */
    public function saveMultiFile($files): array
    {
        $data = [];
        foreach ($files as $key => $file) {
            $result = $this->saveFile($files, $key);
            $data[$key] = $result[$key];
        }
        return $data;
    }

    public function saveImage($data, $attribute, $title, $pathSave )
    {
        $file = $data['image'];
        if (isset($data[$attribute])) {
            $file = $data[$attribute];
            $filename = time(). '_' . $title .'.'. $file->extension();

            $file->move($pathSave, $filename);
            $data[$attribute] = $filename;
        }

        return $data;
    }

    public function deleteImage($fileName,$pathDelele)
    {
        $image_path = $pathDelele . '/' . $fileName;
        if (file_exists($image_path))
            unlink($image_path);
    }
}
