<?php
namespace App\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use App\Models\InvoiceRequestLog;
use App\Models\Master\PortalEnv;
use App\Traits\ToJson;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Invoice{

    use ToJson;
    public $issuer;
    public $receiver;

    public $documentType="I";
    public $documentTypeVersion="1.0";

    public $dateTimeIssued;
    public $taxpayerActivityCode ;
    public $internalID;

    public $purchaseOrderReference = "";
    public $purchaseOrderDescription = "";
    public $salesOrderReference = "";
    public $salesOrderDescription = "";

    public $payment;
    public $delivery;

    public $invoiceLines=[];

    public $totalDiscountAmount =0;
    public $totalSalesAmount =0;
    public $netAmount = 0;
    public $taxTotals =[];

    public $totalAmount = 0;
    public $extraDiscountAmount = 0;
    public $totalItemsDiscountAmount = 0;

    public $signatures=[];

    private $headerId = null;
    protected $arrayItems =[
        'invoiceLines',
        'taxableItems',
        'taxTotals',
    ];
    public function __construct($internalID,$totalSalesAmount=0,$totalDiscountAmount=0,$netAmount=0,$totalAmount=0,$extraDiscount=0)
    {
        $this->issuer = new Issuer(new InvoiceHeader());
        $this->payment = new Payment();
        $this->delivery = new Delivery();
        $this->internalID = $internalID;
        $this->totalSalesAmount = floatval($totalSalesAmount);
        $this->totalDiscountAmount = floatval($totalDiscountAmount);
        $this->netAmount = floatval($netAmount);
        $this->totalAmount = floatval($totalAmount);
        $this->extraDiscountAmount = floatval($extraDiscount);
        $this->taxpayerActivityCode = env("TAX_CODE","2710");
    }

    public function setPayment($payment){
        $this->payment = $payment;
    }
    public function setHeaderId($headerId){
        $this->headerId = $headerId;
    }
    public function setDelivery($delivery){
        $this->delivery = $delivery;
    }

    public function setReciver($receiver){
        $this->receiver = $receiver;
    }

    public function addTaxTotalItme($amount=0,$type="T1"){
        $this->taxTotals[] = [
            "taxType"=>$type,
            "amount"=>floatval($amount)
        ];
    }

    public function addLine($invoiceLine){
        $this->invoiceLines[] = $invoiceLine;
    }

    public function sign($sign=""){
        if($this->documentTypeVersion == "0.9"){
            $this->signatures[] = [
                "signatureType"=>"I",
                "value"=>"n/a"
            ];
        }else{


        }
    }

    public function getInvoiceProps(){
        $data = $this->toJson();
        unset($data['signatures']);
        return $data;
    }

    public function serializeData($data){
        if(!is_object($data) && !is_array($data)){
            return '"'. $data . '"';
        }
        $serializedString = "";
        foreach($data as $name=>$item){
            //for key=>value "description"=>"value"
            if(!in_array($name,$this->arrayItems) && !is_array($item)){
                $serializedString.= '"'.strtoupper($name) . '"';
                $serializedString.= $this->serializeData($item);
            }
             //for key=>[value] "description"=>["k"=>"va"]
            if(!in_array($name,$this->arrayItems) && is_array($item)){
                $serializedString.= '"'.strtoupper($name) . '"';
                $serializedString.= $this->serializeData($item);
            }
            //for array serlization
            if(in_array($name,$this->arrayItems)){
                $serializedString.= '"'.strtoupper($name) . '"';
                foreach($item as $childValue){
                    if(is_object($childValue)){ //for object vlaues
                        $serializedString.= '"'.strtoupper($name) . '"';
                        $serializedString.= $this->serializeData($childValue->toJson());
                    }else{ //for key=>[value] "description"=>["k"=>"va"]
                        $serializedString.= '"'.strtoupper($name) . '"';
                        $serializedString.= $this->serializeData($childValue);
                    }

                }
            }
        }
        return $serializedString;
    }

    public function login($env="Production"){

        $token = "";
        if (Cache::has('token')) {
            $token = Cache::get('token');
            return $token;
        }
        $env = PortalEnv::where('code',$env)->first();
        $response = Http::withOptions([
            'verify' => false
        ])->asForm()->post(env('LOGIN_API',"https://id.eta.gov.eg")."/connect/token", [
            'grant_type' => 'client_credentials',
            'scope' => 'InvoicingAPI',
            'client_id'=>env('CLIENT_KEY'),
            'client_secret'=>env('CLIENT_SECRET')
        ]);
        if($response->successful()){
            $data = $response->json();
            $token = $data['access_token'];
            Cache::put('token', $data['access_token'], $data['expires_in']);
            return $token;
        }
        return false;

    }

    public function getDocumentStatus($uuid,$env="Production"){
        $token = $this->login($env);
        if(!$token)return false;
        $env = PortalEnv::where('code',$env)->first();
        $url = env('ETA_API',"https://api.invoicing.eta.gov.eg")."/api/v1/documents/{$uuid}/raw";
        $log = InvoiceRequestLog::create([
            'invoice_header_id'=>$this->headerId,
            'request_url'=>$url,
            'request_method'=>'GET'
        ]);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)->get($url);
        $this->logErrorInfo($uuid);
        $log->update([
            'response_code'=>$response->status(),
            'response_text'=>$response->body(),
        ]);
        if($response->successful()){
            $data = $response->json();
            return $data['status'];
        }
        return false;

    }
    public function getDocumentResponse($uuid,$env="Production"){
        $token = $this->login($env);
        if(!$token)return false;
        $env = PortalEnv::where('code',$env)->first();
        $url = env('ETA_API',"https://api.invoicing.eta.gov.eg")."/api/v1/documents/{$uuid}/raw";
        $log = InvoiceRequestLog::create([
            'invoice_header_id'=>$this->headerId,
            'request_url'=>$url,
            'request_method'=>'GET'
        ]);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)->get($url);

        if($response->successful()){
            return $response->json();
        }
        return false;

    }
    public function cancelDocument($uuid){
        $token = $this->login();
        if(!$token)return false;
        $url = env('ETA_API',"https://apiinvoicing.eta.gov.eg")."/api/v1.0/documents/Y4JSAV2B0JWJGXZTTNHSWK6F10/state";
        $data = json_encode(['status'=>'cancelled','reason'=>'السعر غير صحيح']);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)
       // ->withBody($data ,'application/json')
        ->put($url,['status'=>'cancelled','reason'=>'السعر غير صحيح']);
        dd($response);
        if($response->successful()){
            $data = $response->json();
            dd($data);
            return $data['status'];
        }
        return false;

    }

    public function submitDocument($env="Production",$invoice){

        $json = $this->toJson();
        $path = base_path() . DIRECTORY_SEPARATOR . "EInvoicing";
        $inputFile = $path. DIRECTORY_SEPARATOR . 'SourceDocumentJson.json';
        //try{
        if(file_exists($path. DIRECTORY_SEPARATOR . 'Cades.txt')){
                unlink($path. DIRECTORY_SEPARATOR . 'Cades.txt');
        }
        if(file_exists($path. DIRECTORY_SEPARATOR . 'CanonicalString.txt')){
            unlink($path. DIRECTORY_SEPARATOR . 'CanonicalString.txt');
        }
        if(file_exists($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json')){
            unlink($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json');
        }
        file_put_contents($inputFile, json_encode($json,JSON_UNESCAPED_UNICODE));
        $cmd =  $path . DIRECTORY_SEPARATOR . 'SubmitInvoices.bat';
        exec($cmd);

        $content = file_get_contents($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json');
        $token = $this->login($env);
        if(!$token)return false;
        $env = PortalEnv::where('code',$env)->first();
        $url = env('ETA_API',"https://api.invoicing.eta.gov.eg")."/api/v1.0/documentsubmissions";
        $log = InvoiceRequestLog::create([
            'invoice_header_id'=>$this->headerId,
            'request_url'=>$url,
            'request_method'=>'POST',
            'request_data'=>$content
        ]);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)
        ->withBody($content,'application/json')
        ->post($url);
        $log->update([
            'response_code'=>$response->status(),
            'response_text'=>$response->body(),
        ]);

        if($response->successful()){
            $data = $response->json();
            $rejected = collect($data['rejectedDocuments']);
            if(count($rejected)>0){
                return "Rejected";
            }
            $accepted = collect($data['acceptedDocuments']);
            if(count($accepted)>0){
                $first = collect($accepted->first());

                $uuid = $first->get('uuid');
                $invoice->update([
                    'portal_id'=>$uuid
                ]);
                if(!is_null($uuid)){
                   // $url = "{$env->system_api}/api/v1/documents/{$uuid}/raw";

                    // $statusResponse = Http::withOptions([
                    //     'verify' => false
                    // ])->withToken($token)->get($url);
                    // if($statusResponse->successful()){
                    //     $data = $response->json();
                    //     $invoice->update([
                    //         'status'=> $data['status']
                    //     ]);
                    // }
                }
                return "Submited";
            }
        }
        return false;

    }


    public function submitQueueDocument($invoice,$signture){
        $this->signatures[] = [
            "signatureType"=>"I",
            "value"=>$signture
        ];
        $json = json_encode($this->toSignJson(),JSON_UNESCAPED_UNICODE);
        //dd() Response::json($this->toSignJson(), 200, [], JSON_UNESCAPED_UNICODE)
       // dd($json);
        //return $json;
        $token = $this->login('Production');
        if(!$token)return false;
        $url = env('ETA_API',"https://api.invoicing.eta.gov.eg")."/api/v1.0/documentsubmissions";
        // $log = InvoiceRequestLog::create([
        //     'invoice_header_id'=>$this->headerId,
        //     'request_url'=>$url,
        //     'request_method'=>'POST',
        //     'request_data'=>$json
        // ]);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)
        ->withBody($json,'application/json')
        ->post($url);
        // $log->update([
        //     'response_code'=>$response->status(),
        //     'response_text'=>$response->body(),
        // ]);
        if($response->successful()){
            $data = $response->json();
            $rejected = collect($data['rejectedDocuments']);
            if(count($rejected)>0){
                return "Rejected";
            }
            $accepted = collect($data['acceptedDocuments']);
            if(count($accepted)>0){
                $first = collect($accepted->first());

                $uuid = $first->get('uuid');
                if(empty($uuid)){
                    $uuid = null;
                }
                $invoice->update([
                    'portal_id'=>$uuid
                ]);
                //if(!is_null($uuid)){
                   // $url = "{$env->system_api}/api/v1/documents/{$uuid}/raw";

                    // $statusResponse = Http::withOptions([
                    //     'verify' => false
                    // ])->withToken($token)->get($url);
                    // if($statusResponse->successful()){
                    //     $data = $response->json();
                    //     $invoice->update([
                    //         'status'=> $data['status']
                    //     ]);
                    // }
                //}
                return "Submited";
            }
        }
        return false;

    }

    public function submitDocumentByQueue($invoice,$queueName){

        $json = $this->toJson();
        $path = base_path() . DIRECTORY_SEPARATOR . "EInvoicing" .DIRECTORY_SEPARATOR. $queueName;
        $inputFile = $path. DIRECTORY_SEPARATOR . 'SourceDocumentJson.json';
        //try{
        if(file_exists($path. DIRECTORY_SEPARATOR . 'Cades.txt')){
            file_put_contents($path. DIRECTORY_SEPARATOR . 'Cades.txt',"");
        }
        if(file_exists($path. DIRECTORY_SEPARATOR . 'CanonicalString.txt')){
            file_put_contents($path. DIRECTORY_SEPARATOR . 'CanonicalString.txt',"");
        }
        if(file_exists($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json')){
            file_put_contents($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json',"");
        }
        file_put_contents($inputFile, json_encode($json,JSON_UNESCAPED_UNICODE));
        $cmd =  $path . DIRECTORY_SEPARATOR . 'SubmitInvoices.bat';
        $res =  Http::get(env('APP_URL')."/sign_invoice",['path'=>$cmd]);
        if(!$res->successful()){
            throw new Exception("Sign Invoice Failed Response");
        }

        $content = file_get_contents($path. DIRECTORY_SEPARATOR . 'FullSignedDocument.json');

        $token = $this->login("Production");
        if(!$token)return false;
       // $env = PortalEnv::where('code',$env)->first();
        $url = env('ETA_API',"https://api.invoicing.eta.gov.eg")."/api/v1.0/documentsubmissions";
        $log = InvoiceRequestLog::create([
            'invoice_header_id'=>$this->headerId,
            'request_url'=>$url,
            'request_method'=>'POST',
            'request_data'=>$content
        ]);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)
        ->withBody($content,'application/json')
        ->post($url);
        $log->update([
            'response_code'=>$response->status(),
            'response_text'=>$response->body(),
        ]);

        if($response->successful()){
            $data = $response->json();
            $rejected = collect($data['rejectedDocuments']);
            if(count($rejected)>0){
                return "Rejected";
            }
            $accepted = collect($data['acceptedDocuments']);
            if(count($accepted)>0){
                $first = collect($accepted->first());

                $uuid = $first->get('uuid');
                $invoice->update([
                    'portal_id'=>$uuid
                ]);
                return "Submited";
            }
        }
        return false;

    }

    public function logDocument($invoice){
        $json = ['documents'=>[$this->toJson()]];
        $log = InvoiceRequestLog::create([
            'invoice_header_id'=>$invoice->id,
            'request_url'=>'Log Document JSON',
            'request_method'=>'LOG',
            'request_data'=>json_encode($json)
        ]);
        return $log;

    }

    public function showDocument($invoice){
        return $this->toJson();

    }

    public function logErrorInfo($uuid){
        try{
            $header = InvoiceHeader::where('portal_id',$uuid)->first();
            if(is_null($header)){
                return;
            }
            $response = $header->getTaxInvoiceModel($header)->getDocumentResponse($header->portal_id,config('app.tax_env',"Production"));
            if(!$response){
                $header->update(['action_name'=>'Recheck Status']);
                return;
            }
            if($response['status'] == 'Valid' || $response['status'] == 'Submitted'){
                $header->update(['status'=>$response['status'],'error_group'=>null,'action_name'=>null,'error_message'=>null]);
                return;
            }

            $step = collect($response['validationResults']['validationSteps'])->filter(function($item){
                return str_contains($item['status'] , 'Invalid');
            })->last();
            if(is_null($step)){
                return;
            }

            if($step['status'] == 'Invalid'){

                $error = $step['error']['innerError'][0];
                $status = null;
                if($error['errorCode'] == 'DS301'){
                    $header->update(['error_group'=>'Diplication']);
                    $msg = $error['error'];
                    $data = explode('UUID: ',$msg);
                    $header->update(['portal_id'=>$data[1]]);
                    $status = $header->getTaxInvoiceModel($header)->getDocumentStatus($header->portal_id,config('app.tax_env',"Production"));
                    if($status){
                        $header->update(['status'=>$status]);
                    }
                    return;
                }
                if($error['errorCode'] == 'ISFX306' || $error['errorCode'] == "4043"){
                    $header->update(['error_group'=>'Signture','error_message'=>$error['error'],'action_name'=>'Resubmit Invoice']);
                   return;
                }
                if($error['errorCode'] == 'TP302'){
                    $header->update(['error_group'=>'Customer ID','error_message'=>$error['error'],'action_name'=>'Fix Customer ID']);
                   return;
                }
                if($error['errorCode'] == 'TP306'){
                    $header->update(['error_group'=>'Seller Id not Registered','error_message'=>$error['error'],'action_name'=>'Fix Seller ID']);
                   return;
                }
                if($error['errorCode'] == 'SF306'){
                    $header->update(['error_group'=>'Customer ID B Length','error_message'=>$error['error'],'action_name'=>'Fix Customer ID']);
                   return;
                }
                if($error['errorCode'] == 'SF307'){
                    $header->update(['error_group'=>'Customer ID P Length','error_message'=>$error['error'],'action_name'=>'Fix Customer ID']);
                   return;
                }
                if($error['errorCode'] == 'NI310'){
                    $header->update(['error_group'=>'Customer ID P Invalid','error_message'=>$error['error'],'action_name'=>'Fix Customer ID']);
                   return;
                }
                if($error['errorCode'] == '310'){
                    $header->update(['error_group'=>'Customer ID Invalid','error_message'=>$error['error'],'action_name'=>'Fix Customer ID']);
                   return;
                }
                if($error['errorCode'] == 'TP309'){
                    $header->update(['error_group'=>'Customer Activity Code Expired','error_message'=>$error['error'],'action_name'=>'Fix Customer Activity Code']);
                   return;
                }
                if($error['errorCode'] == null){
                    $header->update(['error_group'=>'Invalid Signature Format','error_message'=>$error['error'],'action_name'=>'Resubmit Invoice']);
                   return;
                }
                if($error['errorCode'] == null){
                    $header->update(['error_group'=>'Invalid Signature Format','error_message'=>$error['error'],'action_name'=>'Resubmit Invoice']);
                   return;
                }
                if($error['errorCode'] == 'CV313'){
                    $header->update(['error_group'=>'Item Code','error_message'=>$error['error'],'action_name'=>'Check Invoice Item on ETA Portal']);
                   return;
                }else{
                   // dd($error);
                    $header->update(['error_group'=>'Other','error_message'=>$error['error'],'error_code'=>$error['errorCode']]);
                    //dd($error);
                }

            }
        }catch(Throwable $e){
            dd($e);
        }

    }

    public function getDocumentPdf($uuid,$env="Production"){
        $token = $this->login($env);
        if(!$token)return false;
        $env = session('eta_env','production');
        $url = "https://api.invoicing.eta.gov.eg/api/v1/documents/{$uuid}/pdf";
       // dd($url);
        $response = Http::withOptions([
            'verify' => false
        ])->withToken($token)->get($url);
        if($response->successful()){
           return response($response->getBody(),200,$response->getHeaders());
        }
        return false;

    }

}
