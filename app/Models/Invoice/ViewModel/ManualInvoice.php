<?php

namespace App\Models\Invoice\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Builder;


class ManualInvoice extends InvoiceHeader  implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    protected static function booted()
    {
        static::addGlobalScope('src', function (Builder $builder) {
            $builder->where('invoice_src', InvoiceHeader::SRC_MANUAL);
        });
    }

    public function getPermissionActions(){
        return [
            'List',
            'Show',
            'Create',
            'Edit',
            'Submit'
        ];
    }
    public function getPermissionDisplayName(){
        return "Manual Invoice";
    }
}
