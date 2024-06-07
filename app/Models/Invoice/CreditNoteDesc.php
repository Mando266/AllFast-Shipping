<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDesc extends Model
{
    protected $table = 'credit_notes_description';
    protected $guarded = [];


    public function creditNote(){
        return $this->belongsTo(CreditNote::class,'credit_note_id','id');
    }
}
