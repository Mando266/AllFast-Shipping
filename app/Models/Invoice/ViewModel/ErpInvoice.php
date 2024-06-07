<?php

namespace App\Models\Invoice\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ErpInvoice extends InvoiceHeader implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    // protected static function booted()
    // {
    //     static::addGlobalScope('src', function (Builder $builder) {
    //         $builder->whereIn('invoice_src', [InvoiceHeader::SRC_ERP,InvoiceHeader::SRC_API,Invoice]);
    //     });
    // }

    public function getPermissionActions(){
        return [
            'List',
            'Show',
            'Pull',
            'Submit'
        ];
    }

    public function getPermissionDisplayName(){
        return "ERP Invoice";
    }

    public static function connect(){
        // $host = env('DB_HOST_ORA','10.0.11.46');
        // $port = env('DB_PORT_ORA','1531');
        // $db = env('DB_DATABASE_ORA','PROD');
        $c = oci_connect("einvoice2021", "einv321", "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.0.0.240)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = fmst)
    )
  )" ,'AL32UTF8');
        return $c;
    }

    public static function getInvoices($request){
        $c = ErpInvoice::connect();
        $headerTable = env('INVOICE_HEADER','aslm2021.invhead_vu');
        // $defaultFrom = Carbon::now(->format('d-M-Y');
        $fromDate = $request->input('date_from');
        $to_date = $request->input('date_to');
        $type =  $request->input('document_type','i');
      // $sql =  "select * from {$headerTable}";

        $sql =  "select * from {$headerTable} where ( to_date(VOCH_DATE,'DD/MM/YY') BETWEEN to_date('{$fromDate}','DD-MM-YYYY') AND  to_date('{$to_date}','DD-MM-YYYY')) AND VOCH_TYPE = 'I' ORDER BY VOCH_DATE ASC";

        //dd($sql);
        $s = oci_parse($c,$sql);

        oci_execute($s);
        oci_fetch_all($s, $res,null, null, OCI_FETCHSTATEMENT_BY_ROW);
        ErpInvoice::close($c,$s);
        return $res;
    }


    public static function getInvoiceHeader($CUSTOMER_TRX_ID,$voucherNo){
        $c = ErpInvoice::connect();
        $headerTable = env('INVOICE_HEADER','aslm2021.invhead_vu');
        $s = oci_parse($c, "select * from {$headerTable} where IDENT = {$CUSTOMER_TRX_ID} AND VOCH_NO = {$voucherNo}");
        oci_execute($s);
        $row = oci_fetch_assoc($s);
        ErpInvoice::close($c,$s);
        return $row;
    }

    public static function getInvoiceDetails($CUSTOMER_TRX_ID,$row = null){
        $c = ErpInvoice::connect();
        $detailTable = env('INVOICE_DETAIL','aslm2021.invline_vu');
        $s = oci_parse($c, "select * from {$detailTable} where IDENT = {$CUSTOMER_TRX_ID} AND VOCH_NO = {$row['VOCH_NO']}");
        oci_execute($s);
        oci_fetch_all($s, $res,null, null, OCI_FETCHSTATEMENT_BY_ROW);
        ErpInvoice::close($c,$s);
        return $res;
    }

    public static function close($c,$s){
        oci_free_statement($s);
        oci_close($c);
    }

    public static function storeFromOracle($CUSTOMER_TRX_ID,$voucherNo,$replace = false){
        $header = null;
        if($replace){
            $header = InvoiceHeader::where('trx_id',$CUSTOMER_TRX_ID)->where('internal_id',$voucherNo)->first();
            foreach($header->lines as $line){
                $line->taxItems()->delete();
            }
            $header->lines()->delete();
            $header->taxItems()->delete();
            $header->delete();
        }
        //dd($CUSTOMER_TRX_ID,$voucherNo);
        $row = ErpInvoice::getInvoiceHeader($CUSTOMER_TRX_ID,$voucherNo);
        $details = ErpInvoice::getInvoiceDetails($CUSTOMER_TRX_ID,$row);
        $header = InvoiceHeader::where('internal_id',$row['VOCH_NO'])->first();
        if(!is_null($header)){return;}
        DB::transaction(function () use($row,$details) {
            $date = Carbon::createFromFormat('d/m/y',$row['VOCH_DATE']);
            $header = InvoiceHeader::create([
                'invoice_src'=>InvoiceHeader::SRC_ERP,
                'document_type'=>$row['VOCH_TYPE'] ?? 'i',
                'trx_id'=>$row['IDENT'] ?? '',
                'receiver_id'=>$row['CUSTOMER_TAX_CARD'] ?? '',
                'receiver_name'=>$row['CUSTOMER_NAME'] ?? '',
                'receiver_type'=>$row['CUSTOMER_TYPE'] ?? '',
                'receiver_country'=>$row['COUNTRY'] ?? '',
                'receiver_governate'=>$row['GOVERNORATE'] ?? '',
                'reviver_region_city'=>$row['CITY'] ?? '',
                'receiver_street'=>$row['ADDRESS'] ?? '',
                'receiver_building_number'=>$row['BUILDING'] ?? '',
                'date_time_issued'=>$date->toIso8601ZuluString() ?? '',
                'original_date'=>$row['VOCH_DATE'] ?? '',
                'internal_id'=>$row['VOCH_NO'] ?? '',
                'total_discount_amount'=> 0,
                'total_sales_amount'=>0,
                'net_amount'=>0,
                'total_amount'=>0,
                'extra_discount_amount'=>$row['TOTAL_DISCOUNT'],
                'total_items_discount_amount'=>0,
                'purchase_order_reference'=>null,
                'purchase_order_description'=>null,
                'sales_order_reference'=>null,
                'sales_order_description'=>null,
                'payment_bank_name'=>null,
                'payment_bank_address'=>null,
                'payment_bank_account_no'=>null,
                'payment_bank_account_iban'=>null,
                'payment_swift_code'=>null,
                'payment_terms'=>null,
                'delivery_approach'=>null,
                'delivery_packaging'=>null,
                'delivery_date_validity'=>null,
                'delivery_export_port'=>null,
                'delivery_country_origin'=>null,
                'delivery_gross_weight'=>null,
                'delivery_net_weight'=>null,
                'delivery_terms'=>null,
            ]);
            $header->taxItems()->create([
                'tax_type'=>'T1',
                'tax_amount'=>$row['VAT_TAXES']
            ]);

            $header->taxItems()->create([
                'tax_type'=>'T4',
                'tax_amount'=>$row['WITHHODLING_TAXES']
            ]);
            foreach($details as $item){
                $line = $header->lines()->create([
                    'internal_code'=>$item['STOCK_CODE'] ?? '',
                    'description'=>$item['STOCK_NAME'] ?? '',
                   // 'item_type'=>$item['INV_LINES_ITEM_TYPE'] ?? 'GPC',
                    'item_type'=>InvoiceHeader::getItemCodeType($item['ETA_STOCK_CODE']),
                    'item_code'=>$item['ETA_STOCK_CODE'] ?? '',
                    'unit_type'=>$item['ETA_UNIT_CODE'] ?? 'EA',
                    'quantity'=>$item['QTY'] ?? 0,
                    'sales_total'=> 0,
                    'value_difference'=> 0,
                    'total_taxable_fees'=> 0,
                    'net_total'=> 0,
                    'items_discount'=> 0,
                    'total'=> 0,
                    'unit_value_currency_sold'=>$item['CURRENCY_CODE'],
                    'unit_value_amount_egp'=>$item['PRICE'] ?? 0,
                    'unit_value_amount_sold'=>$item['PRICE'] ?? 0,
                    'unit_value_currency_exchange_rate'=> 0,
                    'discount_rate'=> 0,
                    'discount_amount'=> 0,
                ]);
                if(floatval($item['STOCK_SALES_TAXES_AMOUNT']) > 0){
                    $line->taxItems()->create([
                        'tax_type'=> 'T1',
                        'sub_type'=>'V009',
                        'amount'=>0,
                        'rate'=>14,
                    ]);
                }else{
                    $line->taxItems()->create([
                        'tax_type'=> 'T1',
                        'sub_type'=>'V009',
                        'amount'=>0,
                        'rate'=>0,
                    ]);
                }
                if(floatval($item['STOCK_SALES_TAXES_PERC']) > 0){
                    $line->taxItems()->create([
                        'tax_type'=> 'T4',
                        'sub_type'=>'W016',
                        'amount'=>0,
                        'rate'=>1,
                    ]);
                }else{
                    $line->taxItems()->create([
                        'tax_type'=> 'T4',
                        'sub_type'=>'W016',
                        'amount'=>0,
                        'rate'=>0,
                    ]);
                }
            }
            $header->process();
        });
    }
}
