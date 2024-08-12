<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

abstract class AbstractExport extends DefaultValueBinder implements
      FromCollection
    , ShouldQueue
    , WithHeadings
    , WithChunkReading
    , WithEvents
    , WithCustomCsvSettings
    , ShouldAutoSize
{

    use Exportable;

    public $queue = 'long-running';
    public $timeout = 900;

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function get_headers()
    {
        return [];
    }

    public function createMapper()
    {

    }

    public function collection()
    {
        return;
    }

    public function headings(): array
    {
        return $this->get_headers();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $cols = array_keys($sheet->getColumnDimensions());

                foreach ($cols as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $sheet->freezePane('A2');
                $fItem = array_key_first($cols);
                $lItem = array_key_last($cols);

                $cellRange = "$cols[$fItem]1:$cols[$lItem]1";
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

            },
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING2);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

}