<?php

namespace App\Domains\CSV;

use App\Domains\DomainModel;

/**
 * Class Csv
 * @package App\Domains\Csv
 * @property string  $id
 * @property string  $name
 * @property string  $path
 * @property boolean $synced
 */
class CSV extends DomainModel
{
    protected $table = 'csvs';
    protected $casts = [
        'synced' => 'boolean',
    ];
}
