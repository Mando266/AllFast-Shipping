<?php

namespace App\Http\Controllers\Trucker;

use App\Http\Controllers\Controller;
use App\Models\Trucker\Trucker;
use App\Models\Trucker\DelegatedPerson;
use App\Filters\Trucker\TruckerIndexFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckerController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,Trucker::class);
            $truckers = Trucker::filter(new TruckerIndexFilter(request()))->where('company_id',Auth::user()
            ->company_id)->with('delegatedPersons')->paginate(30);
        // dd($truckers);
            return view('trucker.trucker.index',[
                'items'=>$truckers,
            ]);  
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Trucker::class);

        return view('trucker.trucker.create');  
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Trucker::class);
      //  dd($request->input());
        $user = Auth::user();
        $trucker = Trucker::create([
            'company_id'=>$user->company_id,
            'company_name'=> $request->input('company_name'),
            'contact_person'=> $request->input('contact_person'),
            'mobile'=> $request->input('mobile'),
            'phone'=> $request->input('phone'),
            'email'=> $request->input('email'),
            'address'=> $request->input('address'),
            'tax'=> $request->input('tax'),
        ]);

        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $trucker->update(['certificat'=>"certificat/".$path]);
        }

        foreach($request->input('delegatedPerson') as $delegatedPerson){
            DelegatedPerson::create([
                'trucker_id'=>$trucker->id,
                'name'=>$delegatedPerson['name'],
                'degattion_from'=>$delegatedPerson['degattion_from'],
                'degattion_to'=>$delegatedPerson['degattion_to'],
                'id_number'=>$delegatedPerson['id_number'],
                'mobile'=>$delegatedPerson['mobile'],
            ]);
        }

        return redirect()->route('trucker.index')->with('success',trans('Trucker.Created'));

    }

    public function edit(Trucker $trucker)
    {
        $this->authorize(__FUNCTION__,Trucker::class);
        $trucker_person = DelegatedPerson::where('trucker_id',$trucker->id)->with('delegated')->get();

        return view('trucker.trucker.edit',[
            'trucker'=>$trucker,
            'trucker_person'=>$trucker_person,
        ]);  
    }


    public function update(Request $request,  Trucker $trucker)
    {
        $this->authorize(__FUNCTION__,Trucker::class);
        $inputs = request()->all();
        unset($inputs['delegatedPerson'],$inputs['_token'],$inputs['removed']);
        $trucker->update($inputs);
        DelegatedPerson::destroy(explode(',',$request->removed));
        $trucker->createOrUpdateDelegatedPerson($request->delegatedPerson);
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $trucker->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('trucker.index')->with('success',trans('customer.updated.success'));
    }

    public function destroy($id)
    {
        $trucker = Trucker::find($id);
        DelegatedPerson::where('trucker_id',$id)->delete();
        $trucker->delete();
        return back()->with('Success',trans('Trucker.Deleted'));
    }
}
