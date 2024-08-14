<?php

namespace App\Models\Bl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlPrintCounter extends Model
{
    protected $table = 'bl_print_counter';
    
    protected $fillable = [
        'bl_draft_id',
        'print_count',
    ];

    public function blDraft()
    {
        return $this->belongsTo(BlDraft::class, 'bl_draft_id');
    }
}
