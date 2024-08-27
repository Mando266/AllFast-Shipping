<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XML\XmlController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PortChargeController;
use App\Http\Controllers\BlDraft\PDFController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\BlDraft\WinPDFController;
use App\Http\Controllers\Update\RefreshController;
use App\Http\Controllers\BlDraft\BlDraftController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\ReceiptController;
use App\Http\Controllers\Preview\PreviewController;
use App\Http\Controllers\PortChargeInvoiceController;
use App\Http\Controllers\Trucker\TruckerGateController;
use App\Http\Controllers\Quotations\QuotationsController;
use App\Http\Controllers\Quotations\LocalPortTriffDetailesController;
use App\Http\Controllers\DententionStorageCalculation\DententionCalculationPeriodController;
use App\Http\Controllers\DententionStorageCalculation\StorageCalculationPeriodController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    /*
    |-------------------------------------------
    | admin routes
    |--------------------------------------------
    */
    Route::prefix('admin')->namespace('Admin')->group(function () {
        Route::resource('roles', 'RoleController');
        Route::resource('users', 'UserController');
        Route::resource('settings', 'SettingController');
        Route::get('profile', 'UserController@showProfile')->name('profile');
        Route::put('/profile/update/{user}', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::get('reset-password', 'ResetPasswordController@edit')->name('user.reset-password');
        Route::put('reset-password', 'ResetPasswordController@update');
    });
    /*
   |-------------------------------------------
   | master routes
   |--------------------------------------------
   */
    Route::get('cache/clear', function () {
        Artisan::call('cache:clear');
        dd('done');
    });
    Route::prefix('master')->namespace('Master')->group(function () {
        Route::resource('company', 'CompanyController');
        Route::resource('countries', 'CountryController');
        Route::resource('port-types', 'PortTyepsController');
        Route::resource('ports', 'PortsController');
        Route::resource('agents', 'AgentsController');
        Route::resource('terminals', 'TerminalsController');
        Route::resource('line-types', 'LinTypeController');
        Route::resource('lines', 'LinesController');
        Route::resource('suppliers', 'SuppliersController');
        Route::resource('customers', 'CustomersController');
        Route::resource('vessel-types', 'VesselTypeController');
        Route::resource('vessels', 'VesselsController');
        Route::resource('container-types', 'ContinersTypeController');
        Route::resource('containers', 'ContinersController');
        Route::resource('container-movement', 'ContainersMovementController');
        Route::resource('stock-types', 'StockTypesController');
        Route::resource('supplierPrice', 'SupplierPriceController');
        Route::resource('chargesDesc', 'ChargesDescController');
    });
    /*
    |-------------------------------------------
    | Voyage routes
    |--------------------------------------------
    */
    Route::prefix('voyages')->namespace('Voyages')->group(function () {
        Route::resource('voyages', 'VoyagesController');
        Route::get('voyages/{voyage}/{FromPort?}/{ToPort?}', 'VoyagesController@show')->name('voyages.show');
        Route::resource('voyageports', 'VoyageportsController');
    });

    /*
    |-------------------------------------------
    | Containers routes
    |--------------------------------------------
    */
    Route::prefix('containers')->namespace('Containers')->group(function () {
        Route::resource('movements', 'MovementController');
        Route::resource('tracking', 'TrackingController');
        Route::resource('demurrage', 'DemurageController');
        Route::resource('movementerrors', 'MovementImportErrorsController');
        Route::get('detentionView', 'DetentionController@showDetentionView')->name('detention.view');
        Route::post('calculateDetention', 'DetentionController@calculateDetention')->name('detention.calculation');
        Route::get(
            'detention/{id}/{detention}/{dchfDate}/{rcvcDate?}',
            'DetentionController@showTriffSelectWithBlno'
        )->name('detention.showTriffSelectWithBlno');
        Route::post('detention', 'DetentionController@showDetention')->name('detention.showDetention');

        Route::get('/fetch-booking-details', 'MovementController@fetchBookingDetails')->name('booking.fetchDetails');
        Route::get('/fetch-voyage-port-details', [MovementController::class, 'fetchVoyagePortDetails'])->name('fetchVoyagePortDetails');

    });

    /*Excel import export*/
    Route::get('export', 'ImportExportController@export')->name('export');
    Route::get('exportAll', 'ImportExportController@exportAll')->name('export.all');
    Route::post('exportQuotation', 'ImportExportController@exportQuotation')->name('export.quotation');
    Route::get('exportCustomers', 'ImportExportController@exportCustomers')->name('export.customers');
    Route::get('exportLocalporttriffshow', 'ImportExportController@LocalPortTriffShow')->name('export.Localportshow');
    Route::post('exportBooking', 'ImportExportController@exportBooking')->name('export.booking');
    Route::get('exportTruckerGate', 'ImportExportController@exportTruckerGate')->name('export.TruckerGate');
    Route::post('loadlistBooking', 'ImportExportController@loadlistBooking')->name('export.loadList');
    Route::get('loadlistBl', 'ImportExportController@loadlistBl')->name('export.BLloadList');
    Route::get('Bllist', 'ImportExportController@Bllist')->name('export.BLExport');
    Route::get('exportVoyages', 'ImportExportController@exportVoyages')->name('export.voyages');
    Route::get('exportSearch', 'ImportExportController@exportSearch')->name('export.search');
    Route::get('agentSearch', 'ImportExportController@agentSearch')->name('export.agent');
    Route::get('importExportView', 'ImportExportController@importExportView');
    Route::post('import', 'ImportExportController@import')->name('import');
    Route::post('overwrite', 'ImportExportController@overwrite')->name('overwrite');
    Route::post('overwritecont', 'ImportExportController@overwritecont')->name('overwritecont');
    Route::post('importContainers', 'ImportExportController@importContainers')->name('importContainers');
    Route::post('exportContainers', 'ImportExportController@exportContainers')->name('export.container');
    Route::get('invoiceList', 'ImportExportController@invoiceList')->name('export.invoice');
    Route::get('invoiceBreakdown', 'ImportExportController@invoiceBreakdown')->name('export.invoice.breakdown');
    Route::get('exportCalculationForInvoice', 'ImportExportController@exportCalculationForInvoice')->name('export.calculation');
    Route::get('receiptExport', 'ImportExportController@receiptExport')->name('export.receipt');
    Route::get('customerStatementsExport', 'ImportExportController@customerStatementsExport')->name(
        'export.statements'
    );
    Route::get('create-storage-invoice', [InvoiceController::class,'createStorageInvoice'])->name('create-storage-invoice');
    Route::get('create-detention-invoice', [InvoiceController::class,'createDetentionInvoice'])->name('create-detention-invoice');

    /*
    |-------------------------------------------
    | Quotations routes
    |--------------------------------------------
    */
    Route::prefix('quotations')->namespace('Quotations')->group(function () {
        Route::resource('quotations', 'QuotationsController');
        Route::get('{quotation}/approve', [QuotationsController::class, 'approve'])->name('quotation.approve');
        Route::get('{quotation}/reject', [QuotationsController::class, 'reject'])->name('quotation.reject');
        Route::resource('localporttriff', 'LocalPortTriffController');
        Route::get('import', [QuotationsController::class, 'import'])->name('quotation.import');

        Route::get('importcreate', [QuotationsController::class, 'importcreate'])
        ->name('quotation.importcreate');
        Route::get('localporttriffdetailes/{id}', [LocalPortTriffDetailesController::class, 'destroy'])->name(
            'LocalPortTriffDetailes.destroy'
        );
    });
    /*
    |-------------------------------------------
    | Booking routes
    |--------------------------------------------
    */
    Route::prefix('booking')->namespace('Booking')->group(function () {
        Route::resource('booking', 'BookingController');
        Route::get('/booking-details/{bookingId}', [BookingController::class, 'getBookingDetails']);
        Route::get('/check-container', [BookingController::class, 'checkContainer']);
        Route::get('/create-container', [BookingController::class, 'createContainer']);
        Route::get('selectImportQuotation', [BookingController::class, 'selectImportQuotation'])
            ->name('booking.selectImportQuotation');
        Route::get('selectExportQuotation', [BookingController::class, 'selectExportQuotation'])
        ->name('booking.selectExportQuotation');
        Route::get('exportcreate', [BookingController::class, 'exportcreate'])
        ->name('booking.exportcreate');
        Route::get('export', [BookingController::class, 'export'])->name('booking.export');
        Route::get('selectGateOut/{booking}', [BookingController::class, 'selectGateOut'])
            ->name('booking.selectGateOut');
        Route::get('showShippingOrder/{booking}', [BookingController::class, 'showShippingOrder'])
            ->name('booking.showShippingOrder');
        Route::get('deliveryOrder/{booking}', [BookingController::class, 'deliveryOrder'])
            ->name('booking.deliveryOrder');
        Route::get('arrivalNotification/{booking}', [BookingController::class, 'arrivalNotification'])
        ->name('booking.arrivalNotification');
        Route::get('showGateIn/{booking}', [BookingController::class, 'showGateIn'])
            ->name('booking.showGateIn');
        Route::get('showGateInImport/{booking}', [BookingController::class, 'showGateInImport'])
            ->name('booking.showGateInImport');
        Route::get('selectGateInImport/{booking}', [BookingController::class, 'selectGateInImport'])
            ->name('booking.selectGateInImport');
        Route::get('showGateOut/{booking}', [BookingController::class, 'showGateOut'])
            ->name('booking.showGateOut');
        Route::get('showGateOutImport/{booking}', [BookingController::class, 'showGateOutImport'])
            ->name('booking.showGateOutImport');
        Route::get('referManifest', [BookingController::class, 'referManifest'])
            ->name('booking.referManifest');
        Route::get('selectImportBooking', [BookingController::class, 'selectBooking'])
            ->name('booking.selectBooking');
        Route::post('importBooking', [ImportExportController::class, 'importBooking'])
            ->name('importBooking');
        Route::get('{booking}/temperatureDiscrepancy', [BookingController::class, 'temperatureDiscrepancy'])
            ->name('temperature-discrepancy');
        Route::get('{booking}/clone', [BookingController::class, 'clone'])
        ->name('booking.clone');
    });
    /*
    |-------------------------------------------
    | BL routes
    |--------------------------------------------
    */
    Route::prefix('bldraft')->namespace('BlDraft')->group(function () {
        Route::resource('bldraft', 'BlDraftController');
        Route::get('selectBooking', [BlDraftController::class, 'selectBooking'])->name('bldraft.selectbooking');
        Route::get('manifest/{bldraft}', [BlDraftController::class, 'manifest'])->name('bldraft.manifest');
        Route::get('serviceManifest/{bldraft}/{xml?}', [BlDraftController::class, 'serviceManifest'])->name(
            'bldraft.serviceManifest'
        );
        Route::post('incrementPrintCount/{id}', [BlDraftController::class, 'incrementPrintCount'])->name('bldraft.incrementPrintCount');
        Route::get('print-counter', [BlDraftController::class, 'printCounter'])->name('bldraft.printcounter');
        Route::post('bldraft/update-print-counter', [BlDraftController::class, 'updatePrintCounter'])->name('bldraft.updatePrintCounter');
        Route::get('showCstar/{bldraft}', [BlDraftController::class, 'showCstar'])->name('bldraft.showCstar');
        Route::get('pdf', [PDFController::class, 'showPDF'])->name('bldraft.showPDF');
        Route::get('winpdf', [WinPDFController::class, 'showWinPDF'])->name('bldraft.showWinPDF');

    });

    /*
    |-------------------------------------------
    | statements routes
    |--------------------------------------------
    */
    Route::prefix('statements')->namespace('Statements')->group(function () {
        Route::resource('statements', 'CustomerStatementController');
    });
    /*
    |-------------------------------------------
    | Trucker
    |--------------------------------------------
    */
    Route::prefix('trucker')->namespace('Trucker')->group(function () {
        Route::resource('trucker', 'TruckerController');
        Route::resource('truckergate', 'TruckerGateController');
        Route::get('basic_email', [TruckerGateController::class, 'basic_email'])->name('trucker.basic_email');
    });
    /*
    |-------------------------------------------
    | BL routes
    |--------------------------------------------
    */
    Route::prefix('invoice')->namespace('Invoice')->group(function () {
        Route::resource('invoice', 'InvoiceController');
        Route::get('selectBL', [InvoiceController::class, 'selectBL'])->name('invoice.selectBL');
        Route::get('selectBLinvoice', [InvoiceController::class, 'selectBLinvoice'])->name('invoice.selectBLinvoice');
        Route::get('create_invoice', [InvoiceController::class, 'create_invoice'])->name('invoice.create_invoice');
        Route::post('create_invoice', [InvoiceController::class, 'storeInvoice'])->name('invoice.store_invoice');
        Route::post('create/debit', [InvoiceController::class, 'create'])->name('invoice.create_debit');
        Route::resource('receipt', 'ReceiptController');
        Route::get('selectinvoice', [ReceiptController::class, 'selectinvoice'])->name('receipt.selectinvoice');
        Route::resource('refund', 'RefundController');
        Route::resource('creditNote', 'CreditController');
        Route::get('get_invoice_json/{invoice}', 'InvoiceController@invoiceJson')->name('invoice.get_invoice_json');
    });
    /*
    |-------------------------------------------
    | Manual Updates
    |--------------------------------------------
    */
    Route::get('/update/manual', [RefreshController::class, 'updateContainers'])->name('containerRefresh');
    Route::get('/update/quotation', [RefreshController::class, 'updateQuotation'])->name('updateQuotation');
    Route::get('/update/booking/containers/{id?}', [RefreshController::class, 'updateBookingContainers'])->name(
        'bookingContainersRefresh'
    );
    /*
    |-------------------------------------------
    | Storage routes
    |--------------------------------------------
    */
    Route::post('/preview', [PreviewController::class, 'index'])->name('preview.index');
    Route::prefix('storage')->namespace('Storage')->group(function () {
        Route::resource('storage', 'StorageController');
        // Route::get('storage',[StorageController::class,'index'])->name('storage.index');
    });
    Route::prefix('dentention-storage-calculation')->namespace('DententionStorageCalculation')->group(function () {
        Route::resource('dententions', 'DententionController');
        Route::resource('storage', 'StorageController');
        Route::resource('calculation-dentention-period','DententionCalculationPeriodController');
        Route::resource('calculation-storage-period','StorageCalculationPeriodController');
        Route::get('debit-invoice',DebitInvoiceController::class)->name('debit-invoice');
        Route::get('storage-invoice',StorageInvoiceController::class)->name('storage-invoice');
    });
    Route::get('export_dentention_calculation',[DententionCalculationPeriodController::class,'export'])->name('export_dentention_calculation');
    Route::get('export_storage_calculation',[StorageCalculationPeriodController::class,'export'])->name('export_storage_calculation');
    Route::prefix('lessor')->namespace('Master')->group(function () {
        Route::resource('seller', 'LessorSellerController');
    });
    /*
    |-------------------------------------------
    | Manifest XML
    |--------------------------------------------
    */
    Route::prefix('xml')->namespace('XML')->group(function () {
        Route::resource('xml', 'XmlController');
        Route::get('selectManifest', [XmlController::class, 'selectManifest'])->name('xml.selectManifest');
    });

    Route::resource('port-charges', 'PortChargeController')->except(['show']);
    Route::prefix('port-charges')->name('port-charges.')->group(function () {
        Route::post('edit-row', [PortChargeController::class, 'editRow'])->name('edit-row');
        Route::post('delete-row', [PortChargeController::class, 'deleteRow'])->name('delete-row');
        Route::get('get-ref-no', [PortChargeInvoiceController::class, 'getRefNo'])->name('get-ref-no');
        Route::post('calculateInvoiceRow', [PortChargeInvoiceController::class, 'calculateInvoiceRow'])->name(
            'calculate-invoice-row'
        );
    });
    Route::get('port-charge-invoices/export-by-date', [PortChargeInvoiceController::class, 'exportByDateView'])
        ->name('port-charge-invoices.export-date');
    Route::get('port-charge-invoices/booking/{booking}', [PortChargeInvoiceController::class, 'showBooking'])
        ->name('port-charge-invoices.show-booking');
    Route::post('port-charge-invoices/do-export-date', [PortChargeInvoiceController::class, 'doExportByDate'])
        ->name('port-charge-invoices.do-export-date');
    Route::post('port-charge-invoices/export-current', [PortChargeInvoiceController::class, 'exportCurrent'])
        ->name('port-charge-invoices.export-current');
    Route::post('port-charge-invoices/{invoice}/export', [PortChargeInvoiceController::class, 'doExportInvoice'])
        ->name('port-charge-invoices.show.export');
    Route::get('port-charge-invoices/search', [PortChargeInvoiceController::class, 'searchJson'])->name('port-charge-invoices.search');
    Route::get('port-charge-invoices/{portChargeInvoice}/detail-edit', [PortChargeInvoiceController::class, 'detailEdit'])
        ->name('port-charge-invoices.detail-edit');
    Route::patch('port-charge-invoices/{portChargeInvoice}/detail-update', [PortChargeInvoiceController::class, 'detailUpdate'])
        ->name('port-charge-invoices.detail-update');
    Route::resource('port-charge-invoices', 'PortChargeInvoiceController');

});
Auth::routes(['register' => false]);
require 'mail.php';
require 'dev.php';