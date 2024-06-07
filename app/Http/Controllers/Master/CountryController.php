<?php

namespace App\Http\Controllers\Master;

use App\Filters\Master\Country\IndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index(){
        $this->authorize(__FUNCTION__,Country::class);
        $countries = Country::filter(new IndexFilter(request()))
                            ->orderBy('name')
                            ->paginate(30);
        return view('master.country.index',[
            'items'=>$countries,
        ]);
    }
    public function show(Country $country){
        $this->authorize(__FUNCTION__,Country::class);
        return view('master.country.show',[
            'country'=>$country
        ]);
    }

    public function create(){
        $this->authorize(__FUNCTION__,Country::class);
        return view('master.country.create');
    }
    public function store(Request $request){
        $this->authorize(__FUNCTION__,Country::class);
        $this->validate($request,$this->rules());
        Country::create($request->except('_token'));
        return redirect()->route('countries.index')->with('success',trans('country.created'));
    }

    public function edit(Country $country){
        $this->authorize(__FUNCTION__,Country::class);
        return view('master.country.edit',[
            'country'=>$country
        ]);
    }

    public function update(Request $request,Country $country){
        $this->authorize(__FUNCTION__,Country::class);
        $this->validate($request,$this->rules(true,$country));
        $country->update($request->except('_token'));
        return redirect()->route('countries.index')->with('success',trans('country.updated'));
    }

    protected function rules($is_update = false,$model = null){
        $rules =  [
            'name'=>[
                'required','unique'=>Rule::unique('countries')
            ]
        ];
        if($is_update){
            $rules = array_merge($rules, [
                'name'=>[
                    'required','unique'=>Rule::unique('countries')->ignore($model)
                ]
            ]);
        }
        return $rules;
    }
}
