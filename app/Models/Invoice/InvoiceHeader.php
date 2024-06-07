<?php

namespace App\Models\Invoice;

use App\Batch;
use App\Models\Invoice\ViewModel\APIInvoice;
use App\Models\Invoice\ViewModel\ErpInvoice;
use App\Models\Invoice\ViewModel\ManualInvoice;
use App\Models\InvoiceRequestLog;
use App\Models\Master\Company;
use App\Models\Master\Country;
use App\Vendor;
use App\ViewModel\Address;
use App\ViewModel\Invoice;
use App\ViewModel\Issuer;
use App\ViewModel\Payment;
use App\ViewModel\Reciver;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceHeader extends Model
{

    protected static function booted()
    {
        static::addGlobalScope('src', function (Builder $builder) {
            $user = Auth::user();
            if(is_null($user)){
                return;
            }
            if($user->is_super_admin == "1" || $user->is_admin == "1"){
                return;
            }
            if($user->is_group_admin == "1"){
                $builder->where('group_id',$user->group_id);
            }else{
                $builder->where('user_id',$user->id);

            }
            $builder->where('invoice_src','!=',InvoiceHeader::SRC_RECEIVED);
        });
        static::creating(function ($model) {
            $user = Auth::user();
            $model->user_id = optional($user)->id;
            $model->group_id = optional($user)->group_id;
        });

        // static::created(function ($model) {
        //     $hasErrors = intval(!$model->hasErrors());
        //     DB::statement("UPDATE {$model->getTable()}  SET is_valid = {$hasErrors} WHERE id = {$model->id}");
        // });

        // static::updated(function ($model) {
        //     $model->refresh();
        //     $hasErrors = intval(!$model->hasErrors());
        //     DB::statement("UPDATE {$model->getTable()}  SET is_valid = {$hasErrors} WHERE id = {$model->id}");

        // });
    }

    protected $table="invoice_headers";
    protected $guarded = [];
    protected $hidden = ['issue_date'];
    const SRC_MANUAL = "manual";
    const SRC_API = "API";
    const SRC_ERP = "ERP";
    const SRC_EXCEL = "Excel";
    const SRC_RECEIVED = "Recived";

    const STATUS_WAIT_SUBMIT = 'Wait submit';
    const STATUS_WAIT_STATUS = 'Wait status';
    const STATUS_DONE = 'Finished';

    public function taxItems(){
        return $this->hasMany(InvoiceHeaderTax::class,'invoice_header_id','id');
    }

    public function lines(){
        return $this->hasMany(InvoiceLine::class,'invoice_header_id','id');
    }

    public function logs(){
        return $this->hasMany(InvoiceRequestLog::class,'invoice_header_id','id')->latest();
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_code','internal_code');
    }

    public function getReceiverAddressAttribute(){
        return "$this->receiver_building_number , $this->receiver_street - $this->reviver_region_city,$this->receiver_governate , $this->receiver_country";
    }

    public function getDocumentType(){
        if($this->document_type =='c'){
            return "Credit Note";
        }
        if($this->document_type == 'd'){
            return "Debit Note";
        }
        return "Invoice";
    }

    public function getStatusBadgeAttribute(){


        if(strtolower($this->status) == "valid"){
            return "<span class='badge badge-success badge-status'> $this->status </span>";
        }
        if(is_null($this->portal_id)){
            return "<span class='badge badge-warning badge-status'> Not Submited </span>";
        }
        if(!is_null($this->portal_id) && is_null($this->status)){
            return "<span class='badge badge-warning badge-status'> Submited</span>";
        }
        return "<span class='badge badge-warning badge-status'> $this->status  </span>";
    }

    public function getIssuerAddress($company){
        $address = $company->getTaxAddress();
        return "$address->postalCode - $address->buildingNumber , $address->street - $address->regionCity,$address->governate , $this->country";
    }

    public function getPermissionName(){
        if($this->invoice_src == InvoiceHeader::SRC_ERP){
            return basename(ErpInvoice::class);
        }
        if($this->invoice_src == InvoiceHeader::SRC_API){
            return basename(APIInvoice::class);
        }
        if($this->invoice_src == InvoiceHeader::SRC_MANUAL){
            return basename(ManualInvoice::class);
        }
        return basename(static::class);
    }

    public function batch(){
        return $this->belongsTo(Batch::class,'batch_id','id');
    }

    public static function getItemCodeType($itemCode){
        if(is_null($itemCode)){
            return env('ITEM_CODE','EGS');
        }
        return Str::startsWith($itemCode,'EG-') ? 'EGS' : 'GS1';
    }
    public function getShowRoute(){
        if($this->invoice_src == InvoiceHeader::SRC_ERP){
            return route('erp_invoices.show',['erp_invoice'=>$this->id]);
        }
        if($this->invoice_src == InvoiceHeader::SRC_API){
            return route('api_invoices.show',['api_invoice'=>$this->id]);
        }
        if($this->invoice_src == InvoiceHeader::SRC_MANUAL){
            return route('manual_invoices.show',['manual_invoice'=>$this->id]);
        }
        return route('manual_invoices.show',['manual_invoice'=>$this->id]);
    }
    public function cleanName($data){
        if(is_null($data)){
            return "";
        }
        return $data;
        return str_replace('/','-',$data);
    }
    public function cleanDecimal($value){
        $data = $value ?? 0.00;
        return fmod($data,1) !== 0.00 ? $data : number_format(intval($data),2,'.','');
    }

    public function isSubmited(){
        return !is_null($this->portal_id) && strtolower($this->status) == "submitted";
    }
    public function canSubmit(){
        return  empty($this->portal_id) || is_null($this->portal_id ) || strtolower($this->status) == "invalid";
    }
    public function getTaxInvoiceModel($header ,$version = "1.0"){
        $invoice = new Invoice(
            $this->cleanName($this->internal_id),
            $this->cleanDecimal($this->total_sales_amount),
            $this->cleanDecimal($this->total_discount_amount),
            $this->cleanDecimal($this->net_amount),
            $this->cleanDecimal($this->total_amount),
            $this->cleanDecimal($this->extra_discount_amount));
        $invoice->documentType = $this->document_type ?? 'I';
        $issuer = new Issuer($header);
        $address = new Address($this->receiver_governate,$this->reviver_region_city,$this->receiver_street,$this->receiver_building_number,"",$this->receiver_country);
        $reciver = new Reciver($address,$this->receiver_id,$this->receiver_name,$this->receiver_type);
        $invoice->issuer = $issuer;
        $invoice->receiver = $reciver;
        $invoice->dateTimeIssued = $this->date_time_issued;
        $invoice->purchaseOrderReference = $this->cleanName($this->purchase_order_reference);
        $invoice->purchaseOrderDescription = $this->cleanName($this->purchase_order_description);
        $invoice->salesOrderReference =$this->cleanName($this->sales_order_reference);
        $invoice->salesOrderDescription =$this->cleanName($this->sales_order_description);
        $invoice->totalItemsDiscountAmount = floatval($this->cleanDecimal($header->total_items_discount_amount));
        $invoice->totalDiscountAmount = floatval($this->cleanDecimal($header->total_discount_amount));
        $invoice->documentTypeVersion = $version;
        $invoice->setHeaderId($this->id);
        $payment = new Payment(
            $this->cleanName($this->payment_bank_name),
            $this->cleanName($this->payment_bank_address),
            $this->cleanName($this->payment_bank_account_no),
            $this->cleanName($this->payment_bank_account_iban),
            $this->cleanName($this->payment_swift_code),
            $this->cleanName($this->payment_terms)
        );
        $invoice->setPayment($payment);
        $this->taxItems->each(function($item)use($invoice){
            $invoice->addTaxTotalItme($this->cleanDecimal($item->tax_amount),$item->tax_type);
        });
        $this->lines->each(function($item)use(&$invoice){
            $line = new \App\ViewModel\InvoiceLine(
                $this->cleanName($item->description),
                $this->cleanName($item->item_code),
                $this->cleanDecimal($item->quantity),
                $this->cleanName($item->internal_code),
                $this->cleanDecimal($item->sales_total),
                $this->cleanDecimal($item->net_total),
                $this->cleanDecimal($item->items_discount),
                $this->cleanDecimal($item->total),
            );
            $item->taxItems->each(function($taxItem)use($line){
                $line->addTaxItem($this->cleanDecimal($taxItem->amount),$taxItem->tax_type,$taxItem->sub_type,$this->cleanDecimal($taxItem->rate));
            });
            $line->unitType = $item->unit_type ?? "EA";
            $line->itemType = $item->item_type ?? "GS1";
            $line->setDiscount($this->cleanDecimal($item->discount_rate),$this->cleanDecimal($item->discount_amount));
            $line->setUnitValue(
                $this->cleanDecimal($item->unit_value_amount_egp),
                $this->cleanDecimal($item->unit_value_amount_sold),
                $this->cleanDecimal($item->unit_value_currency_exchange_rate),
                $item->unit_value_currency_sold
            );
            $invoice->addLine($line);
        });
        return $invoice;

    }

    public function validate(){
        $errors = [];
        if(empty($this->receiver_type)){
            $errors[] = "Receiver Type is Empty";
        }
        if(strtolower($this->receiver_type) == "f" && $this->receiver_country == "EG"){
            $errors[] = "Receiver Is Foreign and country is Egypt";
        }
        if(strtolower($this->receiver_type) == "p" && floatval($this->total_amount) >= 50000 && empty($this->receiver_id)){
            $errors[] = "Receiver Id is Required";
        }
        if(empty($this->receiver_country)){
            $errors[] = "Receiver country is empty";
        }

        if(strtolower($this->receiver_type) == "b" && empty($this->receiver_id)){
            $errors[] = "Receiver Id is empty";
        }

        if(strtolower($this->receiver_type) == "f" && (is_null($this->receiver_id) ||  $this->receiver_id == "")){
            $errors[] = "Receiver Id is empty";
        }
        if(empty($this->receiver_governate) && empty($this->reviver_region_city)){
           $errors[] = "Reciver Governate and Reciever region city is empty";
        }
        // if(empty($this->receiver_building_number) && !empty($this->receiver_street)){
        //     $this->receiver_building_number = $this->receiver_street;
        // }

        if(empty($this->receiver_building_number)){
            $errors[] = "Reciver Building Number is empty";
        }

        if(empty($this->receiver_country)){
            $errors[] = "Reciver Country code is empty";
        }

        if(empty($this->date_time_issued)){
            $errors[] = "Issue DateTime is empty";
        }
        if(!is_null($this->issue_date) && Carbon::parse($this->issue_date)->greaterThan(Carbon::now())){
            $errors[] = "Issue DateTime is in the future";
        }
        if(!is_null($this->issue_date) && Carbon::parse($this->issue_date)->diffInDays(Carbon::now()) > 7) {
            $errors[] = "Issue DateTime Exceed 7 Dayes";
        }
        foreach($this->lines as $key=>$line){
            $lineErrors = $line->validate($key+1);
            $errors = array_merge($errors,$lineErrors);
        }
        return $errors;
    }

    public function hasErrors(){
        return count($this->validate()) > 0;
    }
    public function process(){
        if(strtolower($this->receiver_type) == "f"){
            $this->receiver_id = "0";
        }
        // if(strtolower($this->receiver_type) == "f"){
        //     if($this->receiver_country == "EG" || empty($this->receiver_country)){
        //         $search = empty($this->reviver_region_city) ?  $this->receiver_governate  : $this->reviver_region_city ;
        //         $country = Country::where('name',$search)->first();
        //         if(!is_null($country)){
        //             $this->receiver_country = $country->prefix;
        //         }
        //     }
        // }
        if(empty($this->receiver_governate) && !empty($this->reviver_region_city)){
            $this->receiver_governate = $this->reviver_region_city;
        }
        if(empty($this->reviver_region_city) && !empty($this->receiver_governate)){
            $this->reviver_region_city = $this->receiver_governate;
        }
        if(empty($this->receiver_building_number) && !empty($this->receiver_street)){
            $this->receiver_building_number = $this->receiver_street;
        }
        /*$date = Carbon::parse($this->date_time_issued);
        $now = Carbon::now();
        if($date->isSameMonth() &&  $now->subDays(3)->gte($date)){
            $date = Carbon::now();
        }
        $this->date_time_issued = substr($date->toISOString(),0,19).'Z';*/
        $totalTax = 0.0;
        $totalT4 = 0.0;
        foreach($this->lines as $line){
           $line->process();
            $totalTax = $totalTax + $line->taxItems->where('tax_type','!=','T4')->sum('amount');
            $totalTax = round($totalTax,5);
            $totalT4 = $totalT4 + $line->taxItems->where('tax_type','T4')->sum('amount');
            $totalT4 = round($totalT4,5);
        }
        $this->total_sales_amount = $this->lines->sum('sales_total');
        $this->net_amount = $this->lines->sum('net_total');
        $totalAmount = 0.0;
        foreach($this->lines as $line){
            $totalAmount += round($line->total,5);
        }
        $extraDiscount = $this->extra_discount_amount ?? 0.0;
        $this->total_amount = $totalAmount - $extraDiscount;
        $this->total_amount = $this->lines->sum('total');
        $this->total_items_discount_amount =  $this->lines->sum('items_discount');
        $this->total_discount_amount = 0.0;
        foreach($this->lines as $line){
            $this->total_discount_amount += $line->discount_amount;
        }
        foreach($this->taxItems as $taxItem){
            if($taxItem->tax_type == "T1"){
                optional($taxItem)->update([
                    'tax_amount' => $totalTax
                ]);
            }else{
                optional($taxItem)->update([
                    'tax_amount' => $totalT4
                ]);
            }

        };
        $this->save();
        $this->refresh();
        $hasErrors = intval(!$this->hasErrors());
        DB::statement("UPDATE {$this->getTable()}  SET is_valid = {$hasErrors} WHERE id = {$this->id}");

    }
    public function getJson(){
        $json = $this->getTaxInvoiceModel($this)->toJson();
        return json_encode($json,JSON_UNESCAPED_UNICODE);
    }
}
