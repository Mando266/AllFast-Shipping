<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

    function edit(Setting $setting)
    {
        $this->authorize(__FUNCTION__,Setting::class);
        return view('admin.settings.edit',[
            'setting'=>$setting,
          
        ]);
    }


    public function update(Request $request, $id)
    {
        $setting = Setting::find(1);
        $setting->update($request->input());
        return redirect()->route('settings.edit',1)->with('success',trans('settings.updated'));
    }

    public function destroy($id)
    {
        //
    }
}
