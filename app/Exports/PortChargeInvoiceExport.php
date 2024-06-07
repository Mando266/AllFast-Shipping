<?php

namespace App\Exports;

use App\Models\Booking\Booking;
use App\Models\PortChargeInvoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PortChargeInvoiceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,
                                         WithStrictNullComparison
{


    protected $invoices;
    protected Collection $rows;

    /**
     * @param PortChargeInvoice|Collection $invoices
     */
    public function __construct($invoices)
    {
        $this->invoices = $invoices;

        if ($invoices instanceof Collection) {
            $this->rows = $invoices->pluck('rows')->collapse();
        } else {
            $this->rows = $invoices->rows;
        }
    }

    public function headings(): array
    {
        return [
            "invoice_no",
            "invoice_date",
            "vessel",
            "voyage",
            "leg",
            "eta",
            "rate",
            "port_charge_name",
            "Shipping Line",
            "service",
            "bl_no",
            "container_no",
            "is_transhipment",
            "shipment_type",
            "quotation_type",
            "currency",
            "thc",
            "storage",
            "power",
            "shifting",
            "disinf",
            "hand_fes_em",
            "gat_lift_off_inbnd_em_ft40",
            "gat_lift_on_inbnd_em_ft40",
            "pti",
            "add_plan",
            "additional_fees",
            "additional_fees_description",
            "Invoice USD",
            "Invoice EGP",
        ];
    }

    public function collection()
    {
        $rows = $this->rows;
        $rows->transform($this->processRow());

        $sums = $rows->reduce($this->calculateSums());
        $spacer = array_fill(0, 15, ''); // Fill with empty strings
        $sums = array_merge($spacer, $sums);

        if ($this->invoices instanceof Collection) {
            $totalUsd = $this->invoices->sum('total_usd');
            $invoiceUsd = $this->invoices->sum('invoice_usd');
            $invoiceEgp = $this->invoices->sum('invoice_egp');
        } else {
            $totalUsd = $this->invoices->total_usd;
            $invoiceUsd = $this->invoices->invoice_usd;
            $invoiceEgp = $this->invoices->invoice_egp;
        }
        $totalRow = array_merge(
            $spacer,
            ['INVOICE EGP', $invoiceEgp, 'INVOICE USD', $invoiceUsd, 'TOTAL USD', $totalUsd]
        );
        $rows->push($sums);
        $rows->push(['']);
        $rows->push($totalRow);
        return $rows;
    }


    public function styles(Worksheet $sheet)
    {
        $sumsRow = $this->rows->count() - 1;
        $totalsRow = $this->rows->count() + 1;

        $totalsRange = "P$totalsRow:AB$totalsRow";
        $sumsRange = "P$sumsRow:AB$sumsRow";

        $totalsStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $sumsStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $checkCell = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5B6874'],
            ],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_DOUBLE],
                'right' => ['borderStyle' => Border::BORDER_DOUBLE],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE],
                'left' => ['borderStyle' => Border::BORDER_DOUBLE],
            ],
        ];

        $sheet->getStyle($sumsRange)->applyFromArray($sumsStyle);
        $sheet->getStyle($totalsRange)->applyFromArray($totalsStyle);
        $sheet->getCell("P$sumsRow")->getStyle()->applyFromArray($checkCell);
        $sheet->getCell("P$totalsRow")->getStyle()->applyFromArray($checkCell);
        $sheet->getCell("R$totalsRow")->getStyle()->applyFromArray($checkCell);
        $sheet->getCell("T$totalsRow")->getStyle()->applyFromArray($checkCell);
        $sheet->mergeCells("U$totalsRow:AB$totalsRow");
    }

    public function processRow(): \Closure
    {
        return function ($row) {
            $invoice = $row->invoice;
            $booking = $row->booking;
            $voyage = $booking->voyage;
            [$invoice_usd, $invoice_egp] = $row->totalCosts();
            return [
                'invoice_no' => $invoice->invoice_no,
                'invoice_date' => $invoice->invoice_date,
                'vessel' => $voyage->vessel->name,
                'voyage' => $voyage->voyage_no,
                'leg' => $voyage->leg->name,
                'eta' => $row->eta,
                'rate' => $invoice->exchange_rate,
                'port_charge_name' => $row->portCharge->name,
                'shipping_line' => $invoice->line->name,
                "service" => $row->service,
                "bl_no" => $row->bl_no,
                "container_no" => $row->container_no,
                "is_transhipment" => $row->is_transhipment,
                "shipment_type" => $row->shipment_type,
                "quotation_type" => $row->quotation_type,
                "currency" => $invoice->invoice_egp > 0 ? "EGP" : "USD",
                "thc" => $row->thc,
                "storage" => $row->storage,
                "power" => $row->power,
                "shifting" => $row->shifting,
                "disinf" => $row->disinf,
                "hand_fes_em" => $row->hand_fes_em,
                "gat_lift_off_inbnd_em_ft40" => $row->gat_lift_off_inbnd_em_ft40,
                "gat_lift_on_inbnd_em_ft40" => $row->gat_lift_on_inbnd_em_ft40,
                "pti" => $row->pti,
                "add_plan" => $row->add_plan,
                "additional_fees" => $row->additional_fees,
                "additional_fees_description" => $row->additional_fees_description,
                "invoice_usd" => $invoice_usd,
                "invoice_egp" => $invoice_egp,
            ];
        };
    }

    public function calculateSums(): \Closure
    {
        return function ($carry, $row) {
            $carry['Total'] = 'TOTAL';
            foreach (
                [
                    "thc",
                    "storage",
                    "power",
                    "shifting",
                    "disinf",
                    "hand_fes_em",
                    "gat_lift_off_inbnd_em_ft40",
                    "gat_lift_on_inbnd_em_ft40",
                    "pti",
                    "add_plan",
                    "additional_fees"
                ] as $key
            ) {
                if (!isset($carry[$key])) {
                    $carry[$key] = 0;
                }
                $carry[$key] += $row[$key];
            }
            return $carry;
        };
    }

}
