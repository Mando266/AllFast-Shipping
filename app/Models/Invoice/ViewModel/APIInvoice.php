<?php

namespace App\Models\Invoice\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Builder;

class APIInvoice extends InvoiceHeader implements PermissionSeederContract
{
    use PermissionSeederTrait;

    protected static function booted()
    {
        static::addGlobalScope('src', function (Builder $builder) {
            $builder->where('invoice_src', InvoiceHeader::SRC_API);
        });
    }
    public function getPermissionDisplayName(){
        return "API Invoices";
    }


    public function getPermissionActions(){
        return [
            'List',
            'Show',
            'Submit'
        ];
    }
}
