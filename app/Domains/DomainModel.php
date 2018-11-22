<?php

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DomainModel extends Model
{
    use Uuid, SoftDeletes;

    public $incrementing = false;
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}