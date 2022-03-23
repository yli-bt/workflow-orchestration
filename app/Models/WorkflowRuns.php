<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class WorkflowRuns extends Model
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
        'input',
        'metadata',
        'start_at',
        'end_at',
        'workflow_uuid'
    ];
}
