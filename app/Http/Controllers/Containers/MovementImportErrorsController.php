<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\MovementImportErrors;
use Illuminate\Http\Request;

class MovementImportErrorsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movementErrors = MovementImportErrors::paginate(30);
        return view('containers.movementerrors.index',[
            'items' => $movementErrors,
        ]);
    }

    public function destroy()
    {
        MovementImportErrors::query()->truncate();
        return redirect()->route('movements.index')->with('success',trans('MovementErrors.deleted.success'));    }
}
