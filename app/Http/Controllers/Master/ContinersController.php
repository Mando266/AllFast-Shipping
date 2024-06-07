<?php

namespace App\Http\Controllers\Master;

use App\Filters\Containers\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainerRepairs;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContinerOwnership;
use App\Models\Master\LessorSeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContinersController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__, Containers::class);

        $container = Containers::filter(new ContainersIndexFilter(request()))->where('company_id', Auth::user()->company_id)->orderBy('id')->paginate(30);
        $exportContainers = request();
        $containers = Containers::where('company_id', Auth::user()->company_id)->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();
        return view('master.containers.index', [
            'items' => $container,
            'containers' => $containers,
            'exportContainers' => $exportContainers,
            'container_types' => $container_types,
            'container_ownership' => $container_ownership,
            'sellers' => LessorSeller::where('company_id', Auth::user()->company_id)->get()

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__, Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();

        return view('master.containers.create', [
            'container_types' => $container_types,
            'container_ownership' => $container_ownership,
            'sellers' => LessorSeller::where('company_id', Auth::user()->company_id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__, Containers::class);

        $request->validate([
            'container_type_id' => 'required',
            'code' => ['required', 'regex:/^[A-Z]{4}[0-9]{7}/', 'string', 'min:11', 'max:11'],
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
            'SOC_COC' => 'required|in:SOC,COC'
        ], [
            'code.regex' => 'Invalid Container Number Format',
            'code.min' => 'Invalid Container Number Format',
            'code.max' => 'Invalid Container Number Format',
            'code.required' => 'Container Number Field is Required',

        ]);
        $user = Auth::user();

        $CodeDublicate = Containers::where('company_id', $user->company_id)->where('code', $request->code)->first();
        if ($CodeDublicate != null) {
            return back()->with('alert', 'This Container Number Already Exists');
        }

        $container = Containers::create([
            'container_type_id'=>$request->input('container_type_id'),
            'code'=>$request->input('code'),
            'iso' => $request->input('iso'),
            'description' => $request->input('description'),
            'tar_weight' => $request->input('tar_weight'),
            'max_payload' => $request->input('max_payload'),
            'container_ownership_id' => $request->input('container_ownership_id'),
            'last_movement' => $request->input('last_movement'),
            'certificat' => $request->input('certificat'),
            'status' => $request->input('status'),
            'activity_location_id' => $request->input('activity_location_id'),
            'is_transhipment' => $request->input('is_transhipment'),
            'type' => $request->input('type'),
            'SOC_COC' => $request->input('SOC_COC'),
        ]);

        foreach($request->input('containerRepairs',[]) as $containerRepairs){
            ContainerRepairs::create([
                'container_id'=>$containerRepairs->id,
                'repair_date'=>$containerRepairs['repair_date'],
                'invoice_no'=>$containerRepairs['invoice_no'],
                'part_code'=>$containerRepairs['part_code'],
                'part_description'=>$containerRepairs['part_description'],
                'supplier'=>$containerRepairs['supplier'],
                'invoice_date'=>$containerRepairs['invoice_date'],
                'qty'=>$containerRepairs['qty'],
                'price'=>$containerRepairs['price'],
                'hours'=>$containerRepairs['hours'],
                'labor'=>$containerRepairs['labor'],
                'total'=>$containerRepairs['total'],
            ]);
        }
        $container->company_id = $user->company_id;
        $container->save();

        if ($request->hasFile('certificat')) {
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $container->update(['certificat' => "certificat/" . $path]);
        }
        return redirect()->route('containers.index')->with('success', trans('container.created'));
    }


    public function edit($id)
    {
        $this->authorize(__FUNCTION__, Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();
        $container = Containers::find($id);
        $container_repairs = ContainerRepairs::where('container_id',$container->id)->get();

        return view('master.containers.edit', [
            'container' => $container,
            'container_types' => $container_types,
            'container_ownership' => $container_ownership,
            'container_repairs' => $container_repairs,
            'sellers' => LessorSeller::where('company_id', Auth::user()->company_id)->get()
        ]);

    }

    public function update(Request $request,Containers $container)
    {
        $this->authorize(__FUNCTION__, Containers::class);
        $request->validate([
            'container_type_id' => 'required',
            'code' => ['required', 'regex:/^[A-Z]{4}[0-9]{7}/', 'string', 'min:11', 'max:11'],
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
        ], [
            'code.regex' => 'Invalid Container Number Format',
            'code.min' => 'Invalid Container Number Format',
            'code.max' => 'Invalid Container Number Format',
            'code.required' => 'Container Number Field is Required',
        ]);

        $user = Auth::user();

        $CodeDublicate = Containers::where('id', '!=', $container->id)->where('company_id', $user->company_id)->where('code', $request->code)->count();
        if ($CodeDublicate > 0) {
            return back()->with('alert', 'This Container Number Already Exists');
        }
        $inputs = request()->except('containerRepairs', '_token', 'removed');
        $container->update($inputs);
        ContainerRepairs::destroy(explode(',',$request->removed));
        $container->createOrUpdateRepairs($request->containerRepairs);

        if ($request->hasFile('certificat')) {
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $container->update(['certificat' => "certificat/" . $path]);
        }
        return redirect()->route('containers.index')->with('success', trans('containers.updated.success'));
    }


    public function destroy($id)
    {
        $container = Containers::Find($id);
        Movements::where('container_id', $id)->delete();
        $container->delete();

        return redirect()->route('containers.index')->with('success', trans('Container.deleted.success'));
    }
}
