<?php

namespace App\Exports;

use App\Filters\Containers\ContainersIndexFilter;
use App\Models\Master\Containers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContainersExport implements FromCollection, WithHeadings
{
    protected $containers;

    /**
     * @param Request $filter
     */
    public function __construct(Request $filter)
    {
        $this->containers = Containers::filter(new ContainersIndexFilter($filter))->where('company_id', Auth::user()->company_id)->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            "id",
            "NUMBER",
            "ISO",
            "TYPE",
            "Container Ownership Type",
            "TAR WEIGHT",
            "MAX PAYLOAD",
            "PRODUCTION YEAR",
            "Container Ownership",
            "SOC/COC",
            "Transhipment OR Not",
            "Notes"
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {

        $containers = $this->containers;
        $exportContainers = collect();
        foreach ($containers ?? [] as $container) {
            if ($container->is_transhipment == 0) {
                $transhipment = 'No';

            } else {
                $transhipment = 'Yes';
            }
            $tempCollection = collect([
                'id' => $container->id,
                'code' => $container->code,
                'iso' => $container->iso,
                'type' => optional($container->containersTypes)->name,
                'ownership' => optional($container->containersOwner)->name,
                'tar_weight' => $container->tar_weight,
                'max_payload' => $container->max_payload,
                'prod_year' => $container->production_year,
                'lessor' => optional($container->seller)->name,
                'soc_coc' => $container->SOC_COC,
                'is_transhipment' => $transhipment,
                'notes' => $container->notes,
            ]);
            $exportContainers->add($tempCollection);
        }

        return $exportContainers;
    }
}
