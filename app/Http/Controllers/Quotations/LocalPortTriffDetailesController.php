<?php

namespace App\Http\Controllers\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Quotations\LocalPortTriffDetailes;
use Illuminate\Http\Request;

class LocalPortTriffDetailesController extends Controller
{
    public function destroy($id)
    {
        $localporttriffDetailes = LocalPortTriffDetailes::find($id);
        $localporttriffDetailes->delete();
    }
}
