<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaServices
{
    private static array $rootPath = [
        'image' => 'Images',
        'video' => 'Videos',
    ];

    private static array $suffix = [
        'image' => '_IMG.',
        'video' => '_VID.',
    ];

    public static function save($media, $type, $path): string
    {
        $name = time() . rand() . self::$suffix[$type] . $media->getClientOriginalExtension();
        Storage::putFileAs('public/'.self::$rootPath[$type] . "/" . $path, $media, $name);
        return self::$rootPath[$type] . "/$path/$name";
    }


    public static function update($media, string $type, ?string $old_path, string $new_path): string
    {
        if (isset($old_path))
            self::delete($old_path);

        return self::save($media, $type, $new_path);
    }

    public static function delete($path): void
    {
        if (Storage::exists('public/'.$path))
            Storage::delete('public/'.$path);
    }
}
