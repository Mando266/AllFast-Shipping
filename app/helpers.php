<?php
namespace App;

use Illuminate\Support\Facades\Auth;


class helpers{

    function is_super_admin()
    {
        return Auth::user()->is_super_admin;
    }
}
