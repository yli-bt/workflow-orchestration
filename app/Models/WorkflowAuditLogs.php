<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class WorkflowAuditLogs extends Model
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
        'action',
        'column',
        'name',
        'old_value',
        'new_value',
        'workflow_uuid'
    ];
}
