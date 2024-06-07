<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\LessorSeller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class LessorSellerController extends Controller
{

    protected $countries;

    public function __construct()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function index()
    {
        $this->authorize(__FUNCTION__, LessorSeller::class);

        $items = LessorSeller::where('company_id',Auth::user()->company_id)->paginate(30);

        return view('master.lessor-seller.index')
            ->with([
                'items' => $items
            ]);
    }


    public function create()
    {
        $this->authorize(__FUNCTION__, LessorSeller::class);

        return view('master.lessor-seller.create')
            ->with([
                'countries' => $this->countries
            ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $lessorSeller = LessorSeller::create($validated);
        $user = Auth::user();
        $lessorSeller->company_id = $user->company_id;
        $lessorSeller->save();
        return redirect()->route('seller.index');
    }


    public function show($id)
    {
        //
    }


    public function edit(LessorSeller $seller)
    {
        $this->authorize(__FUNCTION__, LessorSeller::class);
        return view('master.lessor-seller.edit')
            ->with([
                'countries' => $this->countries,
                'seller' => $seller
            ]);
    }


    public function update(Request $request, LessorSeller $seller)
    {
        $validated = $request->validate($this->rules());
        $seller->update($validated);
        
        return redirect()->route('seller.index');
    }



    public function destroy(LessorSeller $seller)
    {
        $this->authorize(__FUNCTION__, LessorSeller::class);
        $seller->delete();
        return redirect()->route('seller.index');
    }


    public function rules()
    {
        return [
            "name" => ['required', Rule::unique('lessor_sellers')->ignore(request()->name,'name')],
            "country_id" => 'required|integer',
            "city" => '',
            "phone" => '',
            "email" => '',
            "tax" => '',
        ];
    }
}
