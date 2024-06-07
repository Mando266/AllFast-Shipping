<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class LinesWithType extends Model
{
    protected $table = 'lines_with_types';
    protected $guarded = [];

    public $timestamps = false;

    public function line()
    {
        return $this->belongsTo(Lines::class,'line_id','id');
    }
    public function type()
    {
        return $this->belongsTo(LinesType::class,'type_id','id');
    }
}
