<?php

namespace App\Models\Invoice;

use App\User;
use Carbon\Carbon;
use App\Traits\HasFilter;
use App\ViewModel\Issuer;
use App\Models\Bl\BlDraft;
use App\ViewModel\Address;
use App\ViewModel\Payment;
use App\ViewModel\Reciver;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;
use App\Models\Receipt\Receipt;
use App\Models\Voyages\Voyages;
use App\Models\Master\Customers;
use App\ViewModel\ReceiverAddress;
use App\Models\Invoice\InvoiceBooking;
use App\Models\Master\ContainersTypes;
use Illuminate\Database\Eloquent\Model;
use App\ViewModel\Invoice as TaxInvoice;
use App\Models\Invoice\InvoiceChargeDesc;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Invoice extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'invoice';
    protected $guarded = [];

    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }
    public function bldraft(){
        return $this->belongsTo(BlDraft::class,'bldraft_id','id');
    }

    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }

    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type','id');
    }

    public function placeOfAcceptence(){
        return $this->belongsTo(Ports::class,'place_of_acceptence','id');
    }
    public function placeOfDelivery(){
        return $this->belongsTo(Ports::class,'port_of_delivery','id');
    }
    public function loadPort(){
        return $this->belongsTo(Ports::class,'load_port','id');
    }
    public function dischargePort(){
        return $this->belongsTo(Ports::class,'discharge_port','id');
    }

    public function booking(){
        return $this->belongsTo(Booking::class,'booking_ref','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function receipts()
    {
        return $this->hasMany(Receipt::class ,'invoice_id','id');
    }

    public function chargeDesc()
    {
        return $this->hasMany(InvoiceChargeDesc::class ,'invoice_id','id');
    }
    public function customerShipperOrFfw(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

        public function bookings()
        {
        return $this->belongsToMany(Booking::class, 'invoice_booking', 'invoice_id', 'booking_id');
        }
            public function invoiceBooking()
            {
            return $this->hasMany(InvoiceBooking::class ,'invoice_id','id');
            }

    public function createOrUpdateInvoiceChargeDesc($inputs)
    {

        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['invoice_id'] = $this->id;
                if( isset($input['id']) ){
                    InvoiceChargeDesc::find($input['id'])
                    ->update($input);
                }
                else{
                    InvoiceChargeDesc::create($input);
                }
            }
        }
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
    public function getTaxInvoiceModel($version = "1.0"){
        $header = $this;
        $totalEgp = round($this->chargeDesc()->sum('total_egy'),5);
        $egpVat = round($this->chargeDesc()->sum('egp_vat'),5);
        $invoice = new TaxInvoice(
            $this->cleanName($this->invoice_no),
            $this->cleanDecimal($totalEgp),
            $this->cleanDecimal(0),
            $this->cleanDecimal($totalEgp),//gross amount
            $this->cleanDecimal($egpVat),//after vat (net amount)
            $this->cleanDecimal(0));
        $invoice->documentType = $this->document_type ?? 'I';
        $issuer = new Issuer();
        $address = new ReceiverAddress($this->customerShipperOrFfw->city ?? "",$this->customerShipperOrFfw->city ?? "",$this->customerShipperOrFfw->address ?? "","1","",$this->customerShipperOrFfw->country->prefix ?? "");
        $reciver = new Reciver($address,$this->customerShipperOrFfw->tax_card_no,$this->customerShipperOrFfw->name,"B");
        $invoice->issuer = $issuer;
        $invoice->receiver = $reciver;
        $invoice->dateTimeIssued = Carbon::parse($this->date)->subHours(3)->toIso8601ZuluString();
        $invoice->taxpayerActivityCode = "5222"; //Activity Code
        $invoice->purchaseOrderReference = "";
        $invoice->purchaseOrderDescription = "";
        $invoice->salesOrderReference ="";
        $invoice->salesOrderDescription ="";
        $invoice->totalItemsDiscountAmount = 0;
        $invoice->totalDiscountAmount = 0;
        $invoice->documentTypeVersion = $version;
        $invoice->setHeaderId($this->id);
        $payment = new Payment(
            // $this->cleanName($this->payment_bank_name),
            // $this->cleanName($this->payment_bank_address),
            // $this->cleanName($this->payment_bank_account_no),
            // $this->cleanName($this->payment_bank_account_iban),
            // $this->cleanName($this->payment_swift_code),
            // $this->cleanName($this->payment_terms)
        );
        $invoice->setPayment($payment);
        $totalVat = round($this->chargeDesc()->sum('egp_vat') - $this->chargeDesc()->sum('total_egy'),5);
        $invoice->addTaxTotalItme($this->cleanDecimal($totalVat),"T1");
        // $invoice->addTaxTotalItme($this->cleanDecimal($this->tax_discount),"T4");
        $qty = $this->qty;
        $this->chargeDesc->each(function($item)use(&$invoice,$qty){
            $line = new \App\ViewModel\InvoiceLine(
                $this->cleanName($item->charge_description . ' ' . ($this->bldraft->ref_no ?? null)),
                $this->cleanName(strval($item->charge->code)),
                $this->cleanDecimal($item->enabled == "1" ? $qty : 1),
                $this->cleanName($item->charge_description),
                $this->cleanDecimal(round($item->total_egy,5)),
                $this->cleanDecimal(round($item->total_egy,5)),
                $this->cleanDecimal(0),
                $this->cleanDecimal(round($item->egp_vat,5)),
            );
           // $item->taxItems->each(function($taxItem)use($line){
            $vat = round($item->egp_vat - $item->total_egy,5);

            if($item->add_vat == "1"){
                $line->addTaxItem($this->cleanDecimal($vat),"T1","V009",$this->cleanDecimal($this->vat));

            }else{
                $line->addTaxItem($this->cleanDecimal(0),"T1","V001",$this->cleanDecimal(0));
            }

            $rate = $this->rate ?? 1;
            if(optional(optional($this->bldraft)->booking)->voyage_id_second != null && optional(optional($this->bldraft)->booking)->transhipment_port != null){

                if($rate == 'eta'){
                    $rate = optional(optional(optional($this->bldraft)->booking)->secondvoyage)->exchange_rate;
                }elseif($rate == 'etd'){
                    $rate = optional(optional(optional($this->bldraft)->booking)->secondvoyage)->exchange_rate_etd;
                }else{
                    $rate = $this->customize_exchange_rate;
                }
            }else{
                if($rate == 'eta'){
                    $rate = optional(optional($this->bldraft)->voyage)->exchange_rate;
                }elseif($rate == 'etd'){
                    $rate = $this->bldraft->voyage->exchange_rate_etd;
                }else{
                    $rate = $this->customize_exchange_rate;
                }
            }

           // });
            $line->unitType = "EA";
            $line->itemType =  "GS1";
            $line->setDiscount($this->cleanDecimal(0),$this->cleanDecimal(0));
            $currency = "";
            if($this->add_egp == "onlyegp"){
                $line->setUnitValue(
                    $this->cleanDecimal(round($item->size_small * $rate,5)),
                    $this->cleanDecimal(round($item->size_small * $rate,5)),
                    $this->cleanDecimal(1),
                    "EGP"
                );
            }else{
                $line->setUnitValue(
                    $this->cleanDecimal(round($item->size_small * $rate,5)),
                    $this->cleanDecimal(round($item->size_small,5)),
                    $this->cleanDecimal($rate),
                    "USD"
                );
            }

            $invoice->addLine($line);
        });
        return $invoice;

    }

}