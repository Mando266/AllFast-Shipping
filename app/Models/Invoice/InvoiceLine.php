<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $table="invoice_lines";
    protected $guarded = [];

    public function taxItems(){
        return $this->hasMany(InvoiceLineTax::class,'invoice_line_id','id');
    }

    public function header(){
        return $this->belongsTo(InvoiceHeader::class,'invoice_header_id','id');
    }

    public function validate($index){
        $errors = [];
        if(empty($this->internal_code)){
            $errors[] = "Invoice Line {$index} Internal Code is empty";
        }
        if(empty($this->description)){
            $errors[] = "Invoice Line {$index} description is empty";
        }
        if(empty($this->item_code)){
            $errors[] = "Invoice Line {$index} item code is empty";
        }
        if(empty($this->quantity)){
            $errors[] = "Invoice Line {$index} invalid quantity";
        }
        return $errors;
    }

    public function process(){
        $this->unit_value_amount_egp =floatval($this->unit_value_amount_egp);
        $unitValue =  $this->unit_value_amount_egp;
        $this->quantity = floatval($this->quantity);
        $qty = $this->quantity;
        if($this->unit_value_currency_sold != "EGP" && floatval($this->unit_value_amount_sold) == 0.0){
            $this->unit_value_currency_sold = "EGP";
        }
        if($this->unit_value_currency_sold != "EGP"){
            $rate = floatval($this->unit_value_currency_exchange_rate);
            $rateAmout = floatval($this->unit_value_amount_sold);
            $unitValue = $rate * $rateAmout;
            $this->unit_value_amount_egp = $unitValue;
        }
        if($this->unit_value_currency_sold == "EGP"){
            $this->unit_value_amount_sold = 0.0;
        }
        if(floatval($this->discount_rate) > 0.0){
            $this->discount_amount = $this->sales_total * (floatval($this->discount_rate) / 100 );
        }
        $this->discount_amount = floatval($this->discount_amount);
        $this->sales_total = 0.0;
        $this->sales_total =  round($unitValue  * $qty,5);
        
        $this->net_total = $this->sales_total - $this->discount_amount;

        $this->taxItems->each(function($taxItem){
            $taxRate = floatval($taxItem->rate);
            if(strtolower($this->header->receiver_type) == "b" || $taxRate > 0){
                $rate = floatval($taxItem->rate);
                $amount = $this->net_total * ($rate / 100);
                $taxItem->update([
                    'amount'=>$amount
                ]);
            }else{
                $taxItem->update([
                    'amount'=>0,
                    'rate'=>0
                ]);
            }
        });

        $totalTax = $this->taxItems->where('tax_type','!=','T4')->sum('amount');
        $totalT4 = $this->taxItems->where('tax_type','T4')->sum('amount');
        $this->total =  $this->net_total  + ($totalTax - $totalT4) - floatval($this->items_discount);
        $this->save();
    }
}
