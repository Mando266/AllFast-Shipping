<?php

namespace App\Http\Controllers\BlDraft;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;

class PDFController extends Controller
{
    public function showPDF(Request $request)
    {
        $blDraft = BlDraft::where('id',$request->bldraft)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        //dd($etdvoayege);
        // Get the path to the PDF form template
        $templatePath = public_path('bl_template.pdf');

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
        $pdf->SetXY(5, 35);   // shipper
        $pdf->MultiCell(100, 3, optional($blDraft->customer)->name, 0, 'L');

        $pdf->SetXY(5, 38);   // shipper Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_shipper_details)), 0, 'L');

        $pdf->SetXY(5, 61);   // consignee
        $pdf->MultiCell(100, 3, optional($blDraft->customerConsignee)->name, 0, 'L');

        $pdf->SetXY(5, 64);   // consignee Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_consignee_details)), 0, 'L');

        $pdf->SetXY(5, 85);   // notify
        $pdf->MultiCell(100, 3, optional($blDraft->customerNotify)->name, 0, 'L');

        $pdf->SetXY(5, 88);   // notify Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_notifiy_details)), 0, 'L');

        $pdf->SetXY(114, 50);   // bl no 
        $pdf->MultiCell(100, 3, $blDraft->ref_no, 0, 'L');

        $pdf->SetXY(165, 50);   // ref no
        $pdf->MultiCell(100, 3, optional($blDraft->booking)->ref_no, 0, 'L');

        $pdf->SetXY(140, 61);   // vessel voyage
        $pdf->MultiCell(100, 3, optional($blDraft->voyage->vessel)->name .'  '. optional($blDraft->voyage)->voyage_no, 0, 'L');

        $pdf->SetXY(140, 76);   // port of load
        $pdf->MultiCell(100, 3, optional($blDraft->loadPort)->name, 0, 'L');

        $pdf->SetXY(140, 82);   // port of discharge
        $pdf->MultiCell(100, 3, optional($blDraft->dischargePort)->name, 0, 'L');
        
        $pdf->SetXY(140, 88);   // place of Delivery 
        $pdf->MultiCell(100, 3, optional(optional(optional($blDraft->booking)->quotation)->placeOfDelivery)->name, 0, 'L');

        // table
        $pdf->SetXY(60, 130);   // Description 
        $pdf->MultiCell(150, 3, str_replace('<br />', '', nl2br($blDraft->descripions)), 0, 'L'); // Description 

        $pdf->SetXY(80, 190);   // Description 
        if($blDraft->id == 131)
        $pdf->MultiCell(100, 3, 'polar star booking Freight collect', 0, 'L');
        elseif(optional($blDraft->booking->principal)->code == 'PLS')
        $pdf->MultiCell(100, 3, optional($blDraft->booking->principal)->name. ' SOC', 0, 'L');
        elseif(optional($blDraft->booking->principal)->code == 'FLW')
        $pdf->MultiCell(100, 3, optional($blDraft->booking->principal)->name. ' SOC', 0, 'L');
        elseif(optional($blDraft->booking->principal)->code == 'MAS')
        $pdf->MultiCell(100, 3, optional($blDraft->booking->principal)->name. ' COC', 0, 'L');
        else
        $pdf->MultiCell(100, 3, optional($blDraft->booking->principal)->name, 0, 'L');
         
        // Containers 
        if($blDraft->blDetails->count() <= 4){
            $i = 0;
            foreach($blDraft->blDetails as  $bldetails){
                $pdf->SetXY(5, 200+$i);   // container no 
                $pdf->cell(0, 0, optional($bldetails->container)->code, 0, 'L');
    
                $pdf->SetXY(26, 200+$i);   // seal no 
                $pdf->cell(0, 0, $bldetails->seal_no, 0, 'L');
                
                $pdf->SetXY(60, 200+$i);   // packs no & type 
                $pdf->cell(0, 0, $bldetails->packs .' - '. $bldetails->pack_type, 0, 'L');

                
                $pdf->SetXY(165, 200+$i);   // gross
                $pdf->cell(0, 0, $bldetails->gross_weight, 0, 'L');

                if($bldetails->measurement != 0){
                    $pdf->SetXY(190, 200+$i);   // measurement 
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
        $pdf->SetXY(55, 235);   // total packs
        $pdf->cell(0, 0, 'Total No. Of packs  '.$packages, 0, 'L');

        $pdf->SetXY(160, 235);   // total gross
        $pdf->cell(0, 0, 'Total GW  ' .$gross_weight, 0, 'L');

        if($bldetails->measurement != 0){
            $pdf->SetXY(185, 235);   // total measurement 
            $pdf->cell(0, 0, 'Total  '  .$measurement, 0, 'L');
        }

        $pdf->SetXY(162, 245);   // place
        $pdf->cell(0, 0,  optional($blDraft->booking->agent)->city, 0, 'L');

        $pdf->SetXY(180, 245);   // date of issue
        $pdf->cell(0, 0, $etdvoayege->etd, 0, 'L');

        $pdf->SetXY(120, 245);   // date shipping on board
        $pdf->cell(0, 0, $etdvoayege->etd, 0, 'L');

        $pdf->SetXY(132, 249);   // freight charges 
        $pdf->cell(0, 0, $blDraft->payment_kind, 0, 'L');
        // Output the PDF as a string
        $pdfContent = $pdf->Output('filled_form.pdf', 'S', 'UTF-8');
        header('Content-Type: application/pdf; charset=utf-8');
        // header('Content-Disposition: attachment; filename="filled_form.pdf"');
        // header('Content-Length: ' . strlen($pdfContent));
        // Return a view that displays the PDF in an iframe
        echo $pdfContent;
        return view('bldraft.pdf.show', ['pdfContent' => $pdfContent]);
    }
}
