<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Activities extends Model
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
        'name',
        'description',
        'events_dsl',
        'functions_dsl',
        'dsl',
        'created_by',
        'updated_by',
    ];
}
