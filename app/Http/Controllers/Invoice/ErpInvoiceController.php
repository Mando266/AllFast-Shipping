<?php

namespace App\Http\Controllers\Invoice;

use App\Batch;
use App\Export\InvoiceExport;
use App\Filters\InvoiceIndexFilter;
use App\Http\Controllers\Controller;
use App\Imports\InvoiceImport;
use App\Item;
use App\Models\Invoice\InvoiceHeader;
use App\Models\Invoice\InvoiceLine;
use App\Models\Invoice\ViewModel\ErpInvoice;
use App\Models\Invoice\ViewModel\InvoiceHeaderTax;
use App\Models\Invoice\ViewModel\ManualInvoice;
use App\Models\InvoiceRequestLog;
use App\Models\Master\Company;
use App\Models\Master\Country;
use App\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ErpInvoiceController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,ManualInvoice::class);
        if(request()->has('export')){
            ini_set('memory_limit','-1');
            $items = ErpInvoice::filter(new InvoiceIndexFilter(request()))->orderBy('internal_id','ASC')->get();
            return Excel::download(new InvoiceExport($items), 'invoices.xlsx');
        }
        $items = ErpInvoice::filter(new InvoiceIndexFilter(request()))->orderBy('internal_id','ASC');
        $totalNetAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','!=','c')->sum('net_amount');

        $totalSalesAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','!=','c')->sum('total_sales_amount');

        $totalCreditNetAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','c')->sum('net_amount');
        $totalCreditsalesAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','c')->sum('total_sales_amount');

        //dd($totalCreditNetAmount, $totalNetAmount );
        $totalAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','!=','c')->sum('total_amount');
        $totalCreditAmount = ErpInvoice::filter(new InvoiceIndexFilter(request()))->where('document_type','c')->sum('total_amount');
        //$totalTax = 0;

        $totalTax = InvoiceHeaderTax::filter(new InvoiceIndexFilter(request()))->where('document_type','!=','c')->where('invoice_src','!=',InvoiceHeader::SRC_RECEIVED)->sum('tax_amount');
        $totalCreditTax = InvoiceHeaderTax::filter(new InvoiceIndexFilter(request()))->where('document_type','c')->where('invoice_src','!=',InvoiceHeader::SRC_RECEIVED)->sum('tax_amount');

        //foreach($taxItems as $item){

          //  $totalTax += $item->taxItems->sum('tax_amount');
        //}
        $items = $items->with('vendor')->paginate(30);
        $countries = Country::get();
        $vendors = Vendor::get();
        return view('master.invoice.erp.index',[
            'items'=>$items,
            'title'=>trans('menu.erp_invoices'),
            'countries'=>$countries,
            'vendors'=>$vendors,
            'totalNetAmount'=>$totalNetAmount,
            'totalCreditNetAmount'=>$totalCreditNetAmount,
            'totalAmount'=>$totalAmount,
            'totalCreditAmount'=>$totalCreditAmount,
            'totalTax'=>$totalTax,
            'totalCreditTax'=> $totalCreditTax,
            'totalSalesAmount'=> $totalSalesAmount,
            'totalCreditsalesAmount'=>$totalCreditsalesAmount
        ]);
    }

    public function notSubmitedIndex(){
        $this->authorize(__FUNCTION__,ManualInvoice::class);
        if(request()->has('export')){
            $items = ManualInvoice::filter(new InvoiceIndexFilter(request()))->orderBy('internal_id','ASC')->with('taxItems')->get();
            return Excel::download(new InvoiceExport($items), 'invoices.xlsx');
        }
        request()->request->add([
            'status'=>['not-submited']
        ]);
        $items = ManualInvoice::filter(new InvoiceIndexFilter(request()))->orderBy('internal_id','ASC')->with('taxItems')->paginate(30);
        $countries = Country::get();
        return view('master.invoice.erp.index',[
            'items'=>$items,
            'title'=>trans('menu.erp_invoices'),
            'countries'=>$countries
        ]);
    }

    public function create(){
        $rows = [];
        $fromDate = Carbon::now();
        return view('master.invoice.erp.pull-list',[
            'title'=>trans('menu.erp_invoices'),
            'items'=>$rows,
            'fromDate'=>$fromDate->format('d-m-Y')
        ]);
    }

    public function showOracleInvoices(Request $request){
        if($request->has('pull_all')){
            $rows = collect(ErpInvoice::getInvoices($request));
            foreach($rows as $row){
                ErpInvoice::storeFromOracle($row['IDENT'],$row['VOCH_NO']);
            }
            return redirect()->route('erp_invoices.index');
        }
        $rows = ErpInvoice::getInvoices($request);
        $fromDate = Carbon::now();

        return view('master.invoice.erp.pull-list',[
            'items'=>$rows,
            'title'=>trans('menu.erp_invoices'),
            'fromDate'=>$fromDate->format('d-M-Y')

        ]);
    }

    public function pull($CUSTOMER_TRX_ID){
        $voucherNo = request()->input('voucherNo');
        ErpInvoice::storeFromOracle($CUSTOMER_TRX_ID,$voucherNo);
        $invoice = ErpInvoice::where('trx_id',$CUSTOMER_TRX_ID)->where('internal_id',$voucherNo)->first();
        if(!is_null($invoice)){
            return redirect()->route('erp_invoices.show',['erp_invoice'=>$invoice->id]);
        }
        return redirect()->route('erp_invoices.index');

    }

    public function pullAgain($id){
        $invoiceHeader = InvoiceHeader::find($id);
        $trx_id = $invoiceHeader->trx_id;
        $voucherNo = $invoiceHeader->internal_id;
        ErpInvoice::storeFromOracle($trx_id,$voucherNo,true);
        $header = InvoiceHeader::where('trx_id',$trx_id)->where('internal_id',$voucherNo)->first();
        return redirect()->route('erp_invoices.show',['erp_invoice'=>$header->id]);
    }

    public function pullAll(){
        $rows = ErpInvoice::getInvoices(request());
        foreach($rows as $row){
           // ErpInvoice::storeFromOracle($row['CUSTOMER_TRX_ID']);
        }
        return redirect()->route('erp_invoices.index');
    }

    protected function parseXmlDate($dateString){
        if(!is_null($dateString)){
            try{
                $date = Carbon::parse($dateString);
                return $date->toIso8601ZuluString();
            }catch(Exception $ex){
                return "";
            }

        }
        return "";

    }
    protected function getXmlValue($input,$default=""){
        $value =  (string)$input;
        if($value == ""){
            return $default;
        }
        return $value;

    }
    public function xmlUpload(Request $request){
        $content = file_get_contents($request->file('xml_file')->getRealPath());
        $xmlInvoices = simplexml_load_string($content);
        foreach($xmlInvoices as $row){
            DB::transaction(function () use ($row){
                // dd($row->LINE);
                // dd($this->getXmlValue($row->PURCHASE_ORDER_REFERENCE));
                $header = InvoiceHeader::create([
                    'invoice_src'=>InvoiceHeader::SRC_API,
                    'document_type'=>$this->getXmlValue($row->DOCUMENT_TYPE) ?? 'i',
                    'trx_id'=>null,
                    'receiver_id'=>str_replace('-','', $this->getXmlValue($row->RECEIVER_ID)) ?? '',
                    'receiver_name'=>$this->getXmlValue($row->RECEIVER_NAME) ?? '',
                    'receiver_type'=>$this->getXmlValue($row->RECEIVER_TYPE) ?? '',
                    'receiver_country'=>$this->getXmlValue($row->RECEIVER_CODE) ?? '',
                    'receiver_governate'=>$this->getXmlValue($row->RECEIVER_ADDRESS_GOVERNATE) ?? '',
                    'reviver_region_city'=>$this->getXmlValue($row->RECEIVER_REGION_CITY) ?? '',
                    'receiver_street'=>$this->getXmlValue($row->RECEIVER_STREET) ?? '',
                    'receiver_building_number'=>is_string($this->getXmlValue($row->RECEIVER_BUILDING_NUM)) ? $this->getXmlValue($row['RECEIVER_BUILDING_NUM'])  : '',
                    'date_time_issued'=>$this->parseXmlDate($this->getXmlValue($row->DATETIME_ISSUED)),
                    'internal_id'=>$this->getXmlValue($row->INTERNAL_ID) ?? '',
                    'total_discount_amount'=>$this->getXmlValue($row->TOTAL_DISCOUNT_AMT,0) ?? 0,
                    'total_sales_amount'=>abs(floatval($this->getXmlValue($row->TOTAL_SALES_AMT,0))),
                    'net_amount'=>abs(floatval($this->getXmlValue($row->NET_AMT,0))),
                    'total_amount'=>abs(floatval($this->getXmlValue($row->TOTAL_AMOUNT,0))),
                    'extra_discount_amount'=>$this->getXmlValue($row->EXTRA_DISC_AMOUNT,0),
                    'total_items_discount_amount'=>$this->getXmlValue($row->TOTAL_ITEM_DISC_AMOUNT,0),
                    'purchase_order_reference'=>$this->getXmlValue($row->PURCHASE_ORDER_REFERENCE),
                    'purchase_order_description'=>$this->getXmlValue($row->PURCHASE_ORDER_DESCRIPTION),
                    'sales_order_reference'=>$this->getXmlValue($row->SALES_ORDER_REFERENCE),
                    'sales_order_description'=>$this->getXmlValue($row->SALES_ORDER_DESCRIPTION),
                    'payment_bank_name'=>$this->getXmlValue($row->PAYMENT_BANK_NAME),
                    'payment_bank_address'=>$this->getXmlValue($row->PAYMENT_BANK_ADDRESS),
                    'payment_bank_account_no'=>$this->getXmlValue($row->PAYMENT_BANK_ACCOUNTNO),
                    'payment_bank_account_iban'=>$this->getXmlValue($row->PAYMENT_BANK_ACCOUNT_IBAN),
                    'payment_swift_code'=>$this->getXmlValue($row->PAYMENT_SWIFT_CODE),
                    'payment_terms'=>$this->getXmlValue($row->PAYMENT_TERMS),
                    'delivery_approach'=>$this->getXmlValue($row->DELIVERY_APPROACH),
                    'delivery_packaging'=>$this->getXmlValue($row->DELIVERY_PACKAGING),
                    'delivery_date_validity'=>$this->getXmlValue($row->DELIVERY_DATE_VALIDITY),
                    'delivery_export_port'=>$this->getXmlValue($row->DEVLIVERY_EXPORT_PORT),
                    'delivery_country_origin'=>$this->getXmlValue($row->DELIVERY_COUNTRY_OF_ORIGIN),
                    'delivery_gross_weight'=>$this->getXmlValue($row->DELIVERY_GROSS_WEIGHT),
                    'delivery_net_weight'=>$this->getXmlValue($row->DELIVERY_NET_WEIGHT),
                    'delivery_terms'=>$this->getXmlValue($row->DELIVERY_TERMS),
                ]);
                $header->taxItems()->create([
                    'tax_type'=>$this->getXmlValue($row->TAX_TOTAL_TAX_TYPE),
                    'tax_amount'=>$this->getXmlValue($row->TAX_TOTAL_AMT)
                ]);

                foreach($row->LINE as $item){
                    $line = $header->lines()->create([
                        'internal_code'=>$this->getXmlValue($item->INV_LINES_ITEM_CODE) ?? '',
                        'description'=>$this->getXmlValue($item->INV_LINES_DESC) ?? '',
                       // 'item_type'=>$this->getXmlValue($item->INV_LINES_ITEM_TYPE) ?? 'GPC',
                        'item_type'=>'GS1',
                        'item_code'=>'',
                        'unit_type'=>$this->getXmlValue($item->INV_LINES_UNIT_TYPE,'EA') ?? 'EA',
                        'quantity'=>$this->getXmlValue($item->INV_LINES_QTY,0) ?? 0,
                        'sales_total'=>$this->getXmlValue($item->INV_LINES_SALES_TOTAL,0) ?? 0,
                        'value_difference'=>$this->getXmlValue($item->INV_LINES_VAL_DIFF,0) ?? 0,
                        'total_taxable_fees'=>$this->getXmlValue($item->INV_LINES_TOTAL_TAX_FEES,0) ?? 0,
                        'net_total'=>$this->getXmlValue($item->INV_LINES_NET_TOTAL,0) ?? 0,
                        'items_discount'=>$this->getXmlValue($item->INV_LINES_ITEM_DISC,0) ?? 0,
                        'total'=>$this->getXmlValue($item->INV_LINES_TOTAL,0) ?? 0,
                        'unit_value_currency_sold'=>$this->getXmlValue($item->INV_LINES_UNIT_VAL_CURR_SOLD),
                        'unit_value_amount_egp'=>$this->getXmlValue($item->INV_LINES_UNIT_VAL_AMOUNT_EGP,0) ?? 0,
                        'unit_value_amount_sold'=>$this->getXmlValue($item->INV_LINES_UNIT_VAL_AMOUNT_SOLD,0) ?? 0,
                        'unit_value_currency_exchange_rate'=>$this->getXmlValue($item->INV_LINES_UNIT_VAL_CURR_RATE,0) ?? 0,
                        'discount_rate'=>$this->getXmlValue($item->INV_LINES_DISCOUNT_RATE,0) ?? 0,
                        'discount_amount'=>$this->getXmlValue($item->INV_LINES_DISCOUNT_AMT,0) ?? 0,
                    ]);

                    $line->taxItems()->create([
                        'tax_type'=>$this->getXmlValue($item->INV_LINES_TAX_ITEM_TYPE,'T1') ?? 'T1',
                        'sub_type'=>$this->getXmlValue($item->INV_LINES_TAX_ITEM_SUBTYPE,'V009') ?? 'V009',
                        'amount'=>$this->getXmlValue($item->INV_LINES_TAX_ITEM_AMT,0) ?? 0,
                        'rate'=>$this->getXmlValue($item->INV_LINES_TAX_ITEM_RATE,0) ?? 0,
                    ]);
                }
                $header->process();
            });

        }
        return redirect()->route('api_invoices.index');

    }

    public function excelUpload(Request $request){
        $path = $request->file('excel_file')->getRealPath();
        Excel::import(new InvoiceImport(), $path);

        return redirect()->route('api_invoices.index');

    }

    public function show($id){
        $this->authorize(__FUNCTION__,ManualInvoice::class);
        $invoice = InvoiceHeader::with('lines.taxItems','taxItems.taxType')->find($id);
        if(is_null($invoice)){
            return redirect()->back();
        }
        $errors = [];
        if($invoice->status != "Valid"){
            $errors = $invoice->validate();
        }
        $countries = Country::get();
        $next = InvoiceHeader::where('internal_id','>',$invoice->internal_id)->orderBy('internal_id','ASC')->first();
        $pre = InvoiceHeader::where('internal_id','<',$invoice->internal_id)->orderBy('internal_id','DESC')->first();

        $itemCodes = Item::get();
        $batch = null;
        if(!is_null($invoice->batch_id)){
            $batch = Batch::find($invoice->batch_id);

        }
        return view('master.invoice.erp.show',[
            'title'=>trans('menu.erp_invoices'),
            'item'=>$invoice,
            'company' => Company::first(),
            'errors'=>$errors,
            'countries'=>$countries,
            'next'=>$next,
            'pre'=>$pre,
            'itemCodes'=>$itemCodes,
            'batch'=>$batch
        ]);
    }

    public function deleteInvoice($id){
        $invoice = InvoiceHeader::find($id);
        foreach($invoice->lines as $line){
            $line->taxItems()->delete();
        }
        $invoice->lines()->delete();
        $invoice->taxItems()->delete();
        $invoice->delete();
        return redirect()->route('erp_invoices.index');
    }

    public function cancelDocument($id){
        $this->authorize(__FUNCTION__,ErpInvoice::class);
        $invoice = InvoiceHeader::find($id);
        $invoice->update([
            'status'=>null,
            'portal_id'=>null
        ]);
        return redirect()->route('erp_invoices.show',['erp_invoice'=>$id]);
    }

    public function update($id,Request $request){
        //$this->authorize(__FUNCTION__,ManualInvoice::class);
        $invoice = InvoiceHeader::find($id);
        $invoice->update([
            'receiver_id'=>$request->input('receiver_id') ??  $invoice->receiver_id,
            'receiver_country'=>$request->input('receiver_country') ??  $invoice->receiver_country,
            'receiver_governate'=>$request->input('receiver_governate') ??  $invoice->receiver_governate,
            'reviver_region_city'=>$request->input('reviver_region_city') ??  $invoice->reviver_region_city,
            'receiver_street'=>$request->input('receiver_street') ??  $invoice->receiver_street,
            'receiver_building_number'=>$request->input('receiver_building_number') ??  $invoice->receiver_building_number,
            'date_time_issued'=>$request->input('date_time_issued') ?? $invoice->date_time_issued ,
            'receiver_type'=>$request->input('receiver_type') ??  $invoice->receiver_type,
            'portal_id'=>$request->input('portal_id')

        ]);

        foreach($request->input('line',[]) as $key=>$line){

            $invoiceLine = InvoiceLine::find($key);
            if(isset($line['description'])){
                $invoiceLine->description = $line['description'] ?? '';
            }
            if(isset($line['item_code'])){
                $invoiceLine->item_code = $line['item_code'] ?? '';
            }
            if(isset($line['internal_code'])){
                $invoiceLine->internal_code = $line['internal_code'] ?? '';
            }
            $invoiceLine->save();
        }
        $invoice->refresh();
        $hasErrors = intval(!$invoice->hasErrors());
        DB::statement("UPDATE {$invoice->getTable()}  SET is_valid = {$hasErrors} WHERE id = {$invoice->id}");
        $invoice->process();
        return redirect()->route('erp_invoices.show',['erp_invoice'=>$invoice->id]);
    }

    public function checkStatus($id){
        $invoice = InvoiceHeader::find($id);
        $status = $invoice->getTaxInvoiceModel($invoice)->getDocumentStatus($invoice->portal_id,config('app.tax_env',"Production"));
        if($status){
            $invoice->update(['status'=>$status]);
        }
        // if($status == 'Valid'){
        //     $now = Carbon::now();
        //     $submitDate = $now->toDateString();
        //     $c = ManualInvoice::connect();
        //     $s = null;
        //     $lineTrxs = ManualInvoice::getInvoiceDetails($invoice->trx_id);
        //     $lineTrxs = collect($lineTrxs);
        //     foreach($lineTrxs as $item){
        //         $sql ="INSERT INTO apps.EGYPLAST_TAX_STATUS values({$item['CUSTOMER_TRX_ID']} , {$item['CUSTOMER_TRX_LINE_ID']} ,TO_DATE('{$submitDate}','YYYY-MM-DD') , 'valid') ";
        //         $s = oci_parse($c,$sql );
        //         oci_execute($s);
        //     }
        //     ManualInvoice::close($c,$s);
        // }
        return redirect()->route('erp_invoices.show',['erp_invoice'=>$id]);

    }

    public function submitDocument($id){
        $now = Carbon::now();
        $submitDate = $now->toDateString();

        $invoice = InvoiceHeader::find($id);
        if($invoice->isSubmited()){
            $invoice->update(['status'=>null]);
            return redirect()->route('show_document',['id'=>$invoice->id]);
        }
        $invoice->getTaxInvoiceModel($invoice)->submitDocument(config('app.tax_env',"Production"),$invoice);

        return redirect()->route('erp_invoices.show',['erp_invoice'=>$id]);

    }

    public function createCredit($id){
        $credit = new InvoiceHeader();
        DB::transaction(function () use($id,&$credit){
            $invoice = InvoiceHeader::find($id);
            $data = $invoice->getAttributes();
            unset($data['issue_date']);
            unset($data['id']);
            $data['status'] = null;
            $data['portal_id'] = null;
            $credit = new InvoiceHeader($data);
            $credit->document_type = 'c';
            $credit->internal_id = "C-".$invoice->internal_id;
            $credit->date_time_issued = Carbon::now()->subHours(3)->toIso8601ZuluString();
            $credit->save();

            foreach($invoice->taxItems as $tax){
                $attr = $tax->getAttributes();
                unset($attr['id']);
                $credit->taxItems()->create($attr);
            }

            foreach($invoice->lines as $line){
                $attr = $line->getAttributes();
                unset($attr['id']);
                $newLine = $credit->lines()->create($attr);
                foreach($line->taxItems as $tax){
                    $attrTax = $tax->getAttributes();
                    unset($attrTax['id']);
                    $newLine->taxItems()->create($attrTax);
                }
            }


        });

        return redirect()->route('erp_invoices.show',['erp_invoice'=>$credit->id]);
    }

    public function showLog($id){
        $this->authorize(__FUNCTION__,InvoiceRequestLog::class);
        $log = InvoiceRequestLog::find($id);
        return view('master.invoice.erp.log',[
            'title'=>trans('menu.erp_invoices'),
            'invoice'=>$log->invoiceHeader,
            'log'=>$log,
            'data'=>$log->response_text
        ]);
    }

    public function logDocument($id){
        $log = InvoiceRequestLog::find($id);
        $invoice = InvoiceHeader::find($id);
        $model = $invoice->getTaxInvoiceModel($invoice);
        $log = $model->logDocument($invoice);
        return redirect()->route('erp_show-body',['log'=>$log->id]);
    }

    public function showBody($id){
        $this->authorize(__FUNCTION__,InvoiceRequestLog::class);
        $log = InvoiceRequestLog::find($id);
        return view('master.invoice.erp.log',[
            'title'=>trans('menu.manual_invoices'),
            'invoice'=>$log->invoiceHeader,
            'log'=>$log,
            'data'=>$log->request_data
        ]);
    }


    public function destory($id){
        dd($id);
    }
}
