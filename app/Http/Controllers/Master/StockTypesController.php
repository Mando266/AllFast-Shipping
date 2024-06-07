<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\StockTypes;
use Illuminate\Http\Request;

class StockTypesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,StockTypes::class);
        $stock_types = StockTypes::orderBy('id')->paginate(10);

        return view('master.stock-types.index',[
            'items'=>$stock_types,
        ]); 
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,StockTypes::class);
        return view('master.stock-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        StockTypes::create($request->except('_token'));

        return redirect()->route('stock-types.index')->with('success',trans('Stock Type.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(StockTypes $stock_type)
    {
        $this->authorize(__FUNCTION__,StockTypes::class);
        return view('master.stock-types.edit',[
            'stock_type'=>$stock_type,
        ]);    
    }

    public function update(Request $request, StockTypes $stock_type)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,StockTypes::class);
        $stock_type->update($request->except('_token'));
        return redirect()->route('stock-types.index')->with('success',trans('Stock Type.updated.success'));
    }

    public function destroy($id)
    {
        $stock_type =StockTypes::Find($id);
        $stock_type->delete();

        return redirect()->route('stock-types.index')->with('success',trans('Stock Type.deleted.success'));  
    }
}
