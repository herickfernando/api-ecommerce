<?php

namespace App\Domains\Image;

use App\Domains\CRUDService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as BaseImage;

class ImageService extends CRUDService
{
    private static $IMAGESAVEBASEPATH = 'app/public/upload/';
    private static $IMAGEBASEPATH = 'storage/upload/';
    private static $DIRECTORYPATH = 'public/upload/';
    protected $modelClass = Image::class;

    /**
     * @param $image
     * @param $specificPath
     * @return Image
     */
    public function createImage($image, $specificPath)
    {
        $photoName = $this->getNameImage();
        $photoMake = $this->getMakeImage($image);
        $photoFormat = $this->getMimeTypeToExtension($photoMake->mime);

        $photoNameFormat = sprintf('%s.%s', $photoName, $photoFormat);
        $imagePath = $this->getImagePath($specificPath, $photoNameFormat);

        $this->makeDirectory($specificPath);
        $this->recordImage($specificPath, $photoNameFormat, $photoMake);

        return $this->saveImage($imagePath, new $this->modelClass(), $photoNameFormat);
    }

    /**
     * @return string
     */
    private function getNameImage()
    {
        return Carbon
            ::now()
            ->format('Y-m-d-H-i-s-v-u');
    }

    private function getMakeImage($image)
    {
        return BaseImage::make($image);
    }

    private function getMimeTypeToExtension(string $mimeTypeImage)
    {
        $mime = [
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/jpeg' => 'jpg',
        ];

        return $mime[$mimeTypeImage];
    }

    /**
     * @param string $specificPath
     * @param string $photoName
     * @return string
     */
    private function getImagePath(string $specificPath, string $photoName)
    {
        return sprintf('%s%s%s', self::$IMAGEBASEPATH, $specificPath, $photoName);
    }

    /**
     * @param $specificPath
     */
    private function makeDirectory(string $specificPath)
    {
        $directory = sprintf('%s%s', self::$DIRECTORYPATH, $specificPath);
        Storage::makeDirectory($directory);
    }

    /**
     * @param $specificPath
     * @param $photoName
     * @param $imageMake
     */
    private function recordImage(string $specificPath, string $photoName, $imageMake)
    {
        $path = sprintf('%s%s%s', self::$IMAGESAVEBASEPATH, $specificPath, $photoName);
        $storagePath = storage_path($path);

        $imageMake->save($storagePath);
    }

    /**
     * @param string $imagePath
     * @param Image  $modelClass
     * @param string $photoName
     * @return Image
     */
    private function saveImage(string $imagePath, Image $modelClass, string $photoName)
    {
        $modelClass->path = $imagePath;
        $modelClass->name = $photoName;
        $modelClass->save();

        return $modelClass;
    }

    public function updateImage($imageId, $image, $specificPath)
    {
        /** @var Image $model */
        $model = Image::findOrFail($imageId);

        $photoName = $this->getNameImage();
        $photoMake = $this->getMakeImage($image);
        $photoFormat = $this->getMimeTypeToExtension($photoMake->mime);

        $photoNameFormat = sprintf('%s.%s', $photoName, $photoFormat);
        $imagePath = $this->getImagePath($specificPath, $photoNameFormat);

        $this->makeDirectory($specificPath);
        $this->recordImage($specificPath, $photoNameFormat, $photoMake);

        $path = self::$IMAGESAVEBASEPATH . $specificPath . $model->name;

        $storagePath = storage_path($path);

        if (file_exists($storagePath)) {
            unlink($storagePath);
        }

        $this->saveImage($imagePath, $model, $photoNameFormat);

        return $model;
    }
}
