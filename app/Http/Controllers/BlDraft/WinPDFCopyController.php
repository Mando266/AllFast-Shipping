<?php

namespace App\Http\Controllers\BlDraft;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;

class WinPDFCopyController extends Controller
{
    public function showWinCopyPDF(Request $request)
    {
        $blDraft = BlDraft::where('id', $request->bldraft)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id', $blDraft->voyage_id)
                        ->where('port_from_name', optional($blDraft->loadPort)->id)
                        ->first();

        // Get the path to the PDF form template
        $templatePath = public_path('DraftBlWin.pdf');

        // Create a new instance of FPDI
        $pdf = new Fpdi();

        // Set the source file
        $pdf->setSourceFile($templatePath);

        // Add a new page
        $pdf->AddPage();
        // Import the first page of the template
        $tplId = $pdf->importPage(1);
        // Use the imported page as the template for the new page
        $pdf->useTemplate($tplId);
        // Set the font and font size
        $pdf->SetFont('Helvetica');
        $pdf->setFontSize(6);

        // Fill in the form fields with data
        $this->fillPDF($pdf, $blDraft, $etdvoayege);

        // Add an additional page if there are more than 4 BL details
        if ($blDraft->blDetails->count() > 4) {
            $pdf->AddPage(); // Add a blank page
            $this->addAttachedSheet($pdf, $blDraft);
        } else {
            $this->fillContainerDetails($pdf, $blDraft); // Fill containers on the same page
        }

        // Output the PDF to the browser
        $pdf->Output('filled_form.pdf', 'I'); // Change 'I' to 'D' for direct download
    }

    private function fillPDF($pdf, $blDraft, $etdvoayege)
    {
        // Example fields set, add more as per your existing form
        $pdf->SetXY(17, 19); // shipper
        $pdf->MultiCell(100, 3, optional($blDraft->customer)->name, 0, 'L');

        $pdf->SetXY(17, 22); // shipper Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_shipper_details)), 0, 'L');

        $pdf->SetXY(17, 44); // consignee
        $pdf->MultiCell(100, 3, optional($blDraft->customerConsignee)->name, 0, 'L');

        $pdf->SetXY(17, 47); // consignee Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_consignee_details)), 0, 'L');

        $pdf->SetXY(17, 71); // notify
        $pdf->MultiCell(100, 3, optional($blDraft->customerNotify)->name, 0, 'L');

        $pdf->SetXY(17, 74); // notify Details
        $pdf->MultiCell(100, 3, str_replace('<br />', '', nl2br($blDraft->customer_notifiy_details)), 0, 'L');

        $pdf->SetXY(150, 20); // bl no
        $pdf->MultiCell(100, 3, $blDraft->ref_no, 0, 'L');

        $pdf->SetXY(16, 102); // vessel voyage
        $pdf->MultiCell(100, 3, optional($blDraft->voyage->vessel)->name . '  ' . optional($blDraft->voyage)->voyage_no, 0, 'L');

        $pdf->SetXY(56, 102); // port of load
        $pdf->MultiCell(100, 3, optional($blDraft->loadPort)->name, 0, 'L');

        $pdf->SetXY(16, 112); // port of discharge
        $pdf->MultiCell(100, 3, optional($blDraft->dischargePort)->name, 0, 'L');

        $pdf->SetXY(56, 112); // place of Delivery
        $pdf->MultiCell(100, 3, optional(optional(optional($blDraft->booking)->quotation)->placeOfDelivery)->name, 0, 'L');

        $pdf->SetXY(56, 124); // Description
        $pdf->MultiCell(150, 3, str_replace('<br />', '', nl2br($blDraft->descripions)), 0, 'L');
        //dd($blDraft->bl_kind == 'Seaway BL'); 
        if ($blDraft->bl_kind == 'Seaway BL') {
            $pdf->SetXY(155, 177); 
            $pdf->cell(150, 0, 'Seaway Bill Of Lading', 0, 'L');
        }
        $net_weight = 0;
        $gross_weight = 0;
        $measurement = 0;
        $packages = 0;
        foreach ($blDraft->blDetails as $bldetails) {
            $packages += (float)$bldetails->packs;
            $net_weight += (float)$bldetails->net_weight;
            $gross_weight += (float)$bldetails->gross_weight;
            $measurement += (float)$bldetails->measurement;
        }

        $pdf->SetXY(60, 205); // total packs
        $pdf->cell(0, 0, 'Total No. Of packs  ' . $packages, 0, 'L');

        $pdf->SetXY(158, 135); // total gross
        $pdf->cell(0, 0, 'Total GW  ' . $gross_weight, 0, 'L');

        if ($measurement != 0) {
            $pdf->SetXY(185, 235); // total measurement
            $pdf->cell(0, 0, 'Total  ' . $measurement, 0, 'L');
        }

        $pdf->SetXY(131, 268); // place
        $pdf->cell(0, 0, optional(optional($blDraft->booking)->agent)->city, 0, 'L');

        $pdf->SetXY(155, 268); // date of issue
        $pdf->cell(0, 0, optional($etdvoayege)->etd, 0, 'L');

        $pdf->SetXY(90, 258); // freight charges
        $pdf->cell(0, 0, $blDraft->payment_kind, 0, 'L');
        if ($blDraft->bl_kind == 'Seaway BL') {
            $pdf->SetXY(90, 268); // no bl
            $pdf->cell(0, 0, 'Seaway BL', 0, 'L');
        }else{
            $pdf->SetXY(90, 268); // no bl
            $pdf->cell(0, 0, $blDraft->number_of_original, 0, 'L');   
        }

        $pdf->SetXY(16, 258); // freight charges
        $pdf->MultiCell(150, 3, str_replace('<br />', '', nl2br($blDraft->agent_details)), 0, 'L');
    }

    private function fillContainerDetails($pdf, $blDraft)
    {
        $i = 0;
        foreach ($blDraft->blDetails as $bldetails) {
            $pdf->SetXY(16, 187 + $i); // container no
            $pdf->cell(0, 0, optional($bldetails->container)->code, 0, 'L');

            $pdf->SetXY(35, 187 + $i); // seal no
            $pdf->cell(0, 0, $bldetails->seal_no, 0, 'L');

            $pdf->SetXY(60, 187 + $i); // packs no & type
            $pdf->cell(0, 0, $bldetails->packs . ' - ' . $bldetails->pack_type, 0, 'L');

            $pdf->SetXY(165, 187 + $i); // gross
            $pdf->cell(0, 0, $bldetails->gross_weight, 0, 'L');

            if ($bldetails->measurement != 0) {
                $pdf->SetXY(185, 187 + $i); // measurement
                $pdf->cell(0, 0, $bldetails->measurement, 0, 'L');
            }

            $i += 5;
        }
    }
 
    private function addAttachedSheet($pdf, $blDraft)
{
    // Constants for the layout
    $pageHeight = 297; // A4 height in mm
    $marginTop = 30; // Top margin for the table data
    $marginBottom = 15; // Bottom margin to prevent data from going out of the page
    $lineHeight = 10; // Height of each row in mm
    $headerHeight = 10; // Height of the header row in mm
    $usableHeight = $pageHeight - $marginTop - $marginBottom; // Height available for content

    // Calculate rows per page
    $rowsPerPage = intval($usableHeight / ($lineHeight + 1)); // Adding 1 to account for cell borders

    // Headers
    $headers = ['CONTAINER', 'TYPE', 'SEAL No', 'PACKAGES', 'Measurement', 'GR WT(KGS)'];
    $headerWidths = [45, 25, 27, 27, 27, 27]; // Adjusted widths for proper spacing

    // Function to print the title
    $printTitle = function() use ($pdf) {
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetXY(15, 10);
        $pdf->Cell(0, 10, 'ATTACHED SHEET', 0, 1, 'C');
    };

    // Function to print the header
    $printHeader = function(&$y) use ($pdf, $headers, $headerWidths, $headerHeight) {
        $x = 15;
        $pdf->SetFont('Helvetica', 'B', 9);
        foreach ($headers as $index => $header) {
            $pdf->SetXY($x, $y);
            $pdf->Cell($headerWidths[$index], $headerHeight, $header, 1, 0, 'C');
            $x += $headerWidths[$index];
        }
        $y += $headerHeight;
    };

    // Data splitting for pagination
    $detailsChunks = $blDraft->blDetails->chunk($rowsPerPage);

    foreach ($detailsChunks as $chunkIndex => $detailsChunk) {
        if ($chunkIndex > 0) {
            $pdf->AddPage(); // Add a new page for subsequent chunks
        }

        // Initialize Y position
        $y = $marginTop;

        // Add the title and header
        $printTitle();
        $y += 15; // Space for the title and some margin
        $printHeader($y);

        // Print data rows
        $pdf->SetFont('Helvetica', '', 8);
        foreach ($detailsChunk as $detail) {
            $xStart = 15; // Reset X position for each row
            $data = [
                optional($detail->container)->code,
                optional(optional($detail->container)->containersTypes)->name, // Assuming containerType relates to the type of container
                $detail->seal_no,
                $detail->packs . ' - ' . $detail->pack_type,
                $detail->measurement,
                $detail->gross_weight
            ];

            foreach ($data as $index => $value) {
                $pdf->SetXY($xStart, $y);
                $pdf->Cell($headerWidths[$index], $lineHeight, $value, 1);
                $xStart += $headerWidths[$index];
            }
            $y += $lineHeight; // Move to the next row
        }
    }
    
    // $buttonUrl = route('bldraft.showWinPDF', ['bldraft' => $blDraft]);
    // $buttonX = 20; 
    // $buttonY = 260; 
    // $buttonWidth = 50;
    // $buttonHeight = 10;
    // $buttonText = 'Print';
    // $pdf->SetFillColor(0, 102, 204); 
    // $pdf->Rect($buttonX, $buttonY, $buttonWidth, $buttonHeight, 'DF'); 
    // $pdf->SetTextColor(255, 255, 255); 
    // $pdf->SetFont('Helvetica', '', 12);
    // $pdf->SetXY($buttonX, $buttonY);
    // $pdf->Cell($buttonWidth, $buttonHeight, $buttonText, 0, 0, 'C', false, $buttonUrl);
    // $pdf->Output('filled_form.pdf', 'I');
}
}
