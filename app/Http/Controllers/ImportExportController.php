<?php

namespace App\Http\Controllers;

use App\Exports\AgentsExportSearch;
use App\Exports\BLExport;
use App\Exports\BLLoadListExport;
use App\Exports\BookingExport;
use App\Exports\CalculationExport;
use App\Exports\ContainersExport;
use App\Exports\CustomerExport;
use App\Exports\CustomerStatementsExport;
use App\Exports\InvoiceBreakdownExport;
use App\Exports\InvoiceListExport;
use App\Exports\LoadListExport;
use App\Exports\LocalPortTriffShowExport;
use App\Exports\MovementsExport;
use App\Exports\MovementsExportAll;
use App\Exports\MovementsExportSearch;
use App\Exports\QuotationExport;
use App\Exports\ReceiptExport;
use App\Exports\TruckerGateExport;
use App\Exports\VoyageExport;
use App\Imports\BookingImport;
use App\Imports\ContainersImport;
use App\Imports\ContainersOvewriteImport;
use App\Imports\MovementsImport;
use App\Imports\MovementsOvewriteImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportExportController extends Controller
{
    public function importExportView()
    {
        return view('importexport');
    }

    public function import()
    {
        Excel::import(new MovementsImport, request()->file('file'));
        return back();
    }

    public function importBooking()
    {
        Excel::import(new BookingImport, request()->file('file'));
        return back();
    }

    public function overwrite()
    {
        Excel::import(new MovementsOvewriteImport, request()->file('file'));
        return back();
    }

    public function overwritecont()
    {
        Excel::import(new ContainersOvewriteImport, request()->file('file'));
        return back();
    }

    public function export()
    {
        return $this->exportWithValidation(new MovementsExport, 'Movements.xlsx', 'items');
    }

    public function exportAll()
    {
        return Excel::download(new MovementsExportAll, 'Movements.xlsx');
    }

    public function exportQuotation(): BinaryFileResponse
    {
        return Excel::download(new QuotationExport(request()), 'Quotations.xlsx');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportCalculationForInvoice(): BinaryFileResponse
    {
        return $this->exportWithValidation(new CalculationExport(), 'InvoiceCalculation.xlsx', 'calculations');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportContainers(): BinaryFileResponse
    {
        return Excel::download(new ContainersExport(request()), 'Containers.xlsx');
    }

    public function importContainers()
    {
        Excel::import(new ContainersImport, request()->file('file'));
        return back();
    }

    public function exportTruckerGate()
    {
        return $this->exportWithValidation(new TruckerGateExport, 'TruckerGate.xlsx', 'truckergates');
    }

    public function LocalPortTriffShow()
    {
        return $this->exportWithValidation(new LocalPortTriffShowExport, 'LocalPortTriff.xlsx', 'TriffNo');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportBooking(): BinaryFileResponse
    {
        return Excel::download(new BookingExport(request()), 'Bookings.xlsx');
    }

    /**
     * @return BinaryFileResponse
     */
    public function loadlistBooking(): BinaryFileResponse
    {
        return Excel::download(new LoadListExport(request()), 'Loadlist.xlsx');
    }

    public function exportVoyages()
    {
        return $this->exportWithValidation(new VoyageExport, 'Voyages.xlsx', 'voyages');
    }

    public function exportSearch()
    {
        return $this->exportWithValidation(new MovementsExportSearch, 'Movements.xlsx', 'items');
    }

    public function agentSearch()
    {
        return $this->exportWithValidation(new AgentsExportSearch, 'AgentReport.xlsx', 'items');
    }

    public function loadlistBl()
    {
        return $this->exportWithValidation(new BLLoadListExport, 'BLloadList.xlsx', 'bldarft');
    }

    public function invoiceList()
    {
        return $this->exportWithValidation(new InvoiceListExport, 'InvoiceList.xlsx', 'invoice');
    }

    public function invoiceBreakdown()
    {
        return $this->exportWithValidation(new InvoiceBreakdownExport, 'InvoiceBreakdownList.xlsx', 'invoice');
    }

    public function exportCustomers()
    {
        return $this->exportWithValidation(new CustomerExport, 'Customers.xlsx', 'customers');
    }

    public function Bllist()
    {
        return $this->exportWithValidation(new BLExport, 'BLExport.xlsx', 'bldarft');
    }

    public function receiptExport()
    {
        return $this->exportWithValidation(new ReceiptExport, 'ReceiptExport.xlsx', 'receipts');
    }

    public function customerStatementsExport()
    {
        return $this->exportWithValidation(new CustomerStatementsExport, 'CustomerStatements.xlsx', 'statements');
    }

    private function exportWithValidation($export, $filename, $sessionKey)
    {
//        if (!session()->has($sessionKey)) {
//            return $this->errorMsg();
//        }

        return Excel::download($export, $filename);
    }

    private function errorMsg()
    {
        return back()->with('message', 'Session Expired! Refresh the page and Try Again.');
    }
}
