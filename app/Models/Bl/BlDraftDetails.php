<?php

namespace App\Models\Bl;

use App\Models\Master\Containers;
use App\Traits\HasFilter;

use Illuminate\Database\Eloquent\Model;

class BlDraftDetails extends Model
{
    use HasFilter;
    protected $table = 'bl_draft_details';
    protected $guarded = [];

    public function blDraft(){
        return $this->belongsTo(BlDraft::class,'bl_id','id');
    }
    public function container(){
        return $this->belongsTo(Containers::class,'container_id','id');
    }
}
