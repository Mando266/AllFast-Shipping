<?php

use Illuminate\Http\Request;
use App\Models\Master\Company;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AgentCountry;
use App\Http\Controllers\API\PortController;
use App\Http\Controllers\API\PriceController;
use App\Http\Controllers\API\BlDraftController;
use App\Http\Controllers\API\CountriesController;
use App\Http\Controllers\API\DemurrageController;
use App\Http\Controllers\API\CompanyDataController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\API\DententionController;
use App\Http\Controllers\API\StorageContainersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('get-company-users',function(){
        $copmany =  Company::find(request()->input('company_id'));
        if(!is_null($copmany)){
            return $copmany->users()->get(['users.id','users.name']);
        }
        return [];
    });


//});
Route::get('vessel/voyages/{id}', [CompanyDataController::class, 'getVesselVoyages']);
Route::get('vessel/multi-voyages/{id}', [CompanyDataController::class, 'getMultiVesselVoyages']);
Route::get('master/ports/{id}', [CompanyDataController::class, 'portsCountry']);
Route::get('master/customers/{id}', [CompanyDataController::class, 'customer']);
Route::get('master/terminals/{id}', [CompanyDataController::class, 'terminalsPorts']);
Route::get('master/requesttype/{name}', [CompanyDataController::class, 'requestType']);
Route::get('agent/loadPrice/{id}/{equipment_id?}/{company_id}', [PriceController::class, 'getLoadAgentPrice']);
Route::get('agent/dischargePrice/{id}/{equipment_id?}/{company_id}', [PriceController::class, 'getDischargeAgentPrice']);
Route::get('agent/agentCountry/{company_id}/{id}', [AgentCountry::class, 'getAgentCountry']);
Route::get('booking/activityContainers/{company_id}/{equipment_id}', [CountriesController::class, 'getActivityContainers']);
Route::get('master/invoices/{id}', [CompanyDataController::class, 'blinvoice']);
Route::get('master/invoicesCustomers/{id}', [CompanyDataController::class, 'customerInvoice']);
Route::get('/bldrafts/{bldraft}/containers', [BlDraftController::class ,'containers']);
Route::get('bl/is-export/{blDraft}', [BlDraftController::class, 'isExportJson']);
Route::get('storage/bl/containers/{id}/{company_id}', [StorageContainersController::class, 'getStorageBlContainers']);
Route::get('storage/triffs/{service}/{company_id}', [StorageContainersController::class, 'getStorageTriffs']);
Route::get('/get-ports', [PortController::class, 'getPorts'])->name('api.get-ports');
Route::get('get_invoice_json/{id}','Invoice\InvoiceController@invoiceJson');
Route::get('validate/demurrage/{companyId}/{portId}/{from}/{to}/{triffType}', [DemurrageController::class, 'checkTriffOverlap']);
Route::get('/blno', [DententionController::class,'getBlnoToBookingNo'])->name('api.blno');
Route::get('/bl-containers', [DententionController::class,'getBlContainers'])->name('api.blContainers');