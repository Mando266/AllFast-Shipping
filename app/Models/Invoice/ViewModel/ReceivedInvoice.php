<?php

namespace App\Models\Invoice\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReceivedInvoice extends InvoiceHeader implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    protected static function booted()
    {
        static::addGlobalScope('src', function (Builder $builder) {
            $builder->whereIn('invoice_src', [InvoiceHeader::SRC_RECEIVED]);
        });
    }

    public function getPermissionActions(){
        return [
            'List',
            'Show'
        ];
    }

    public function getPermissionDisplayName(){
        return "Received Invoice";
    }
    public function getAddress(){
        return "$this->issuer_building_number , $this->issuer_street - $this->issuer_region_city,$this->issuer_governate , $this->issuer_country";
    }

}
