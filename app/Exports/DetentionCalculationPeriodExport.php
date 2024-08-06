<?php

namespace App\Exports;

use Carbon\Carbon;


class DetentionCalculationPeriodExport extends AbstractExport
{

    protected $data;


    public function __construct($data)
    {
        $this->data = $data['containers'];
    }

    public function collection()
    {
        return $this->data->map(function ($item,$index) {
            return [
                ++$index,
                $item['container_no'],
                $item['container_type'],
                $item['bl_no'],
                $item['from'],
                $item['to'],
                $item['daysCount'],
                $item['freeTime'],
                '0',
                '0',
                '0',
                $item['total']!=0 ? $item['total'] :'0',
            ];
        });
    }


    public function get_headers()
    {
        return [
            '#',
            'cntr',
            'type',
            'BL no',
            'DCHF',
            'RCVC',
            'Total days',
            'FREE DAYS',
            'DM days',
            '10-14 days (20DC - 33usd, 40HC-66usd)',
            '15 day + onward (20DC-66usd, 40HC-132usd)',
            'usd',
           
        ];
    }
}