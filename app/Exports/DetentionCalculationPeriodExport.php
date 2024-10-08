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

            $DM_days = max($item['daysCount'] - $item['freeTime'], 0);
            $days_slabs = $item['periods']->map(function ($period) {
                return $period['name'].' =>'.$period['days'] . '| ';
            })->toArray();
            return [
                ++$index,
                $item['container_no'],
                $item['container_type'],
                $item['bl_no'],
                $item['from'],
                $item['to'],
                $item['daysCount'],
                "{$item['freeTime']}",
                "$DM_days",
                implode("", $days_slabs),
                "{$item['total']}"
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
            'Slabs Details',
            'usd',
           
        ];
    }
}