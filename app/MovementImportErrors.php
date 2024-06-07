<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovementImportErrors extends Model
{
    protected $table = 'movement_import_errors';
    protected $fillable = [
        'id',
        'date',
        'container_id',
        'error_code',
        'allowed_code'
    ];
}
