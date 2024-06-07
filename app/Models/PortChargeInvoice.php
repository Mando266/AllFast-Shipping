<?php

namespace App\Models;

use App\Models\Master\Country;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use Illuminate\Database\Eloquent\Model;

class PortChargeInvoice extends Model
{
    protected $guarded = [];


    public const COSTS = [
        'thc',
        'storage',
        'power',
        'shifting',
        'disinf',
        'hand_fes_em',
        'gat_lift_off_inbnd_em_ft40',
        'gat_lift_on_inbnd_em_ft40',
        'pti',
        'add_plan',
        'additional_fees',
        'otbnd',
    ];

    public function rows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PortChargeInvoiceRow::class);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function line(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lines::class, 'shipping_line_id');
    }

    public function port(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ports::class, 'port_id');
    }

    public function portChargeInvoiceVoyages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PortChargeInvoiceVoyage::class);
    }

    public function voyages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Voyages::class,
            PortChargeInvoiceVoyage::class,
            'port_charge_invoice_id',
            'voyages_id'
        );
    }

    public function vessels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Vessels::class,
            'port_charge_invoice_voyages',
            'port_charge_invoice_id',
            'vessel_id'
        );
    }


    public function voyagesNames(): string
    {
        return $this->voyages->isNotEmpty()
            ? $this->voyages->pluck('voyage_no')->unique()->implode(',')
            : '';
    }

    public function vesselsNames(): string
    {
        return $this->vessels->isNotEmpty()
            ? $this->vessels->pluck('name')->unique()->implode(',')
            : '';
    }

    public function getSelectedCostsAttribute($value)
    {
        $wordsToRemove = ["power_days", "storage_days", "pti_type"];
        $selectedArray = explode(",", $value);
        return implode(",", array_diff($selectedArray, $wordsToRemove));
    }


    public static function searchQuery($request): \Illuminate\Database\Eloquent\Builder
    {
        $term = $request->q;
        $from = $request->from;
        $to = $request->to;
        $lineIds = $request->line_id;
        $payer = $request->payer;
        $portChargeIds = [
            'local' => [1, 2],
            'foreign' => [3, 4, 5, 6],
        ];
        $cost = $request->cost;
        $bl_no = $request->bl_no;
        $container_no = $request->container_no;

        return static::query()->where(function ($q) use ($term) {
            $q->where('invoice_no', 'like', "%{$term}%")
                ->orWhereHas('rows', fn($q) => $q->where('container_no', 'like', "%{$term}%"))
                ->orWhereHas('rows', fn($q) => $q->where('bl_no', 'like', "%{$term}%"))
                ->orWhereHas('country', fn($q) => $q->where('name', 'like', "%{$term}%"))
                ->orWhereHas('port', fn($q) => $q->where('name', 'like', "%{$term}%"))
                ->orWhereHas('vessels', fn($q) => $q->where('name', 'like', "%{$term}%"))
                ->orWhereHas('voyages', fn($q) => $q->where('voyage_no', 'like', "%{$term}%"));
        })
            ->when(isset($from), fn($q) => $q->whereDate('invoice_date', '>=', $from))
            ->when(isset($to), fn($q) => $q->whereDate('invoice_date', '<=', $to))
            ->when(isset($lineIds), fn($q) => $q->whereIn('shipping_line_id', $lineIds))
            ->when(isset($cost), fn($q) => $q->where('selected_costs', 'like', "%$cost%"))
            ->when(isset($bl_no), fn($q) => $q->whereHas('rows', fn($query) => $query->where('bl_no', $bl_no)))
            ->when(
                isset($container_no),
                fn($q) => $q->whereHas('rows', fn($query) => $query->where('container_no', $container_no))
            )
            ->when(
                isset($payer),
                fn($q) => $q->whereHas('rows', fn($query) => $query->whereIn('port_charge_id', $portChargeIds[$payer]))
            );
    }

    public function createVoyageCosts($voyage, $costs): Model
    {
        return $this->portChargeInvoiceVoyages()->create([
            'voyages_id' => $voyage->id,
            'vessel_id' => $voyage->vessel->id,
            'empty_costs' => $costs['empty_costs'] ?? null,
            'full_costs' => $costs['full_costs'] ?? null,
        ]);
    }

    public function fullCount(): int
    {
        return $this->rows->pluck('quotation_type')->filter(fn($s) => strtolower($s) === "full")->count();
    }

    public function emptyCount(): int
    {
        return $this->rows->pluck('quotation_type')->filter(fn($s) => strtolower($s) === "empty")->count();
    }

}
