<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevPassController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dev_tools.dev-pass');
    }
}
