<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Workflows extends Model
{
    protected $primaryKey = 'uuid';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'version',
        'spec_version',
        'name',
        'friendly_name',
        'hash',
        'publish_status',
        'has_dsl',
        'class_path',
        'dsl',
        'is_callable',
    ];
}
