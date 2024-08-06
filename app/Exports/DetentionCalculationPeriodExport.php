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

            $DM_days = $item['daysCount'] - $item['freeTime'];
            $first_slab = isset($item['periods'][0]) ?  $item['periods'][0]['days']: 0;
            $second_slab = isset($item['periods'][1]) ? $item['periods'][1]['days']: 0;
            return [
                ++$index,
                $item['container_no'],
                $item['container_type'],
                $item['bl_no'],
                $item['from'],
                $item['to'],
                $item['daysCount'],
                $item['freeTime'],
                "$DM_days",
                "$first_slab",
                "$second_slab",
                "{$item['total']}",
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