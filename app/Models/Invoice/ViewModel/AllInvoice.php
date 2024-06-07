<?php

namespace App\Models\Invoice\ViewModel;

use App\Models\Invoice\InvoiceHeader;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Builder;

class AllInvoice extends InvoiceHeader implements PermissionSeederContract
{
    use PermissionSeederTrait;

    public function getPermissionDisplayName(){
        return "All Invoices";
    }


    public function getPermissionActions(){
        return [
            'List',
            'Show',
        ];
    }
}
