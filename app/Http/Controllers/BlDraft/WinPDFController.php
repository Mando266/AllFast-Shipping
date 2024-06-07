<?php

namespace App\Http\Controllers\BlDraft;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;

class WinPDFController extends Controller
{
    public function showWinPDF(Request $request)
    {
        $blDraft = BlDraft::where('id',$request->bldraft)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        //dd($etdvoayege);
        // Get the path to the PDF form template
        $templatePath = public_path('winwin.pdf');

        // Create a new instance of FPDI
        $pdf = new \setasign\Fpdi\Fpdi();

        // Set the source file
        $pdf->setSourceFile($templatePath);

        // Add a new page
        $pdf->AddPage();
        // Import the first page of the template
        $tplId = $pdf->importPage(1);

        // Use the imported page as the template for the new page
        $pdf->useTemplate($tplId);

        // Set the font
        $pdf->SetFont('Helvetica');

        // Set the font size
        $pdf->setFontSize(6);

        // Fill in the form fields with data
        $pdf->SetXY(16, 20);   // shipper
        $pdf->MultiCell(100, 3, optional($blDraft->customer)->name, 0, 'L');

        $pdf->SetXY(16, 23);   // shipper Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_shipper_details)), 0, 'L');

        $pdf->SetXY(16, 44);   // consignee
        $pdf->MultiCell(100, 3, optional($blDraft->customerConsignee)->name, 0, 'L');

        $pdf->SetXY(16, 47);   // consignee Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_consignee_details)), 0, 'L');

        $pdf->SetXY(16, 72);   // notify
        $pdf->MultiCell(100, 3, optional($blDraft->customerNotify)->name, 0, 'L');

        $pdf->SetXY(16, 75);   // notify Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_notifiy_details)), 0, 'L');

        $pdf->SetXY(149, 20);   // bl no 
        $pdf->MultiCell(100, 3, $blDraft->ref_no, 0, 'L');

        // $pdf->SetXY(165, 30);   // ref no
        // $pdf->MultiCell(100, 3, optional($blDraft->booking)->ref_no, 0, 'L');

        $pdf->SetXY(16, 104);   // vessel voyage
        $pdf->MultiCell(100, 3, optional($blDraft->voyage->vessel)->name .'  '. optional($blDraft->voyage)->voyage_no, 0, 'L');

        $pdf->SetXY(56, 104);   // port of load
        $pdf->MultiCell(100, 3, optional($blDraft->loadPort)->name, 0, 'L');

        $pdf->SetXY(16, 114);   // port of discharge
        $pdf->MultiCell(100, 3, optional($blDraft->dischargePort)->name, 0, 'L');
        
        $pdf->SetXY(56, 114);   // place of Delivery 
        $pdf->MultiCell(100, 3, optional(optional(optional($blDraft->booking)->quotation)->placeOfDelivery)->name, 0, 'L');

        // table
        $pdf->SetXY(56, 124);   // Description 
        $pdf->MultiCell(150, 3, str_replace('<br />', '', nl2br($blDraft->descripions)), 0, 'L'); // Description 

        // $pdf->SetXY(60, 189);   // Description 
        // $pdf->MultiCell(100, 3, optional($blDraft->booking->principal)->name. ' COC', 0, 'L');
         
        // Containers 
        if($blDraft->blDetails->count() <= 4){
            $i = 0;
            foreach($blDraft->blDetails as  $bldetails){
                $pdf->SetXY(16, 189+$i);   // container no 
                $pdf->cell(0, 0, optional($bldetails->container)->code, 0, 'L');
    
                $pdf->SetXY(35, 189+$i);   // seal no 
                $pdf->cell(0, 0, $bldetails->seal_no, 0, 'L');
                
                $pdf->SetXY(60, 189+$i);   // packs no & type 
                $pdf->cell(0, 0, $bldetails->packs .' - '. $bldetails->pack_type, 0, 'L');

                
                $pdf->SetXY(165, 189+$i);   // gross
                $pdf->cell(0, 0, $bldetails->gross_weight, 0, 'L');

                if($bldetails->measurement != 0){
                    $pdf->SetXY(185, 189+$i);   // measurement 
                    $pdf->cell(0, 0, $bldetails->measurement, 0, 'L');
                }
                
                $i = $i + 5;
            }
        }
        $net_weight = 0;
        $gross_weight = 0;
        $measurement = 0;
        $packages = 0;
        foreach($blDraft->blDetails as $blkey => $bldetails){
            $packages = $packages + (float)$bldetails->packs;
            $net_weight = $net_weight + (float)$bldetails->net_weight;
            $gross_weight = $gross_weight + (float)$bldetails->gross_weight;
            $measurement = $measurement + (float)$bldetails->measurement;
        }
        $pdf->SetXY(60, 208);   // total packs
        $pdf->cell(0, 0, 'Total No. Of packs  '.$packages, 0, 'L');

        $pdf->SetXY(158, 135);   // total gross
        $pdf->cell(0, 0, 'Total GW  ' .$gross_weight, 0, 'L');

        if($bldetails->measurement != 0){
            $pdf->SetXY(185, 235);   // total measurement 
            $pdf->cell(0, 0, 'Total  '  .$measurement, 0, 'L');
        }

        $pdf->SetXY(131, 269);   // place
        $pdf->cell(0, 0,  optional(optional($blDraft->booking)->agent)->city, 0, 'L');

        $pdf->SetXY(155, 269);   // date of issue
        $pdf->cell(0, 0, optional($etdvoayege)->etd, 0, 'L');

        // $pdf->SetXY(120, 245);   // date shipping on board
        // $pdf->cell(0, 0, optional($etdvoayege)->etd, 0, 'L');

        $pdf->SetXY(90, 260);   // freight charges 
        $pdf->cell(0, 0, $blDraft->payment_kind, 0, 'L');

        $pdf->SetXY(90, 270);   // no bl 
        $pdf->cell(0, 0, $blDraft->number_of_original, 0, 'L');
        // Output the PDF as a string
        $pdfContent = $pdf->Output('filled_form.pdf', 'S', 'UTF-8');
        header('Content-Type: application/pdf; charset=utf-8');
        echo $pdfContent;
        return view('bldraft.pdf.show', ['pdfContent' => $pdfContent]);
    }
}
