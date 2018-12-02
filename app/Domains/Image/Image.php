<?php

namespace App\Domains\Image;

use App\Domains\DomainModel;

/**
 * Class Image
 * @package App\Domains\Image
 * @property string $id
 * @property string $name
 * @property string $path
 */
class Image extends DomainModel
{
    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        return asset($this->path);
    }
}
