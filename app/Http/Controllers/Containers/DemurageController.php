<?php

namespace App\Http\Controllers\Containers;

use App\Filters\Containers\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Containers\Bound;
use App\Models\Containers\DemuragePeriodsSlabs;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Period;
use App\Models\Containers\Triff;
use App\Models\Master\ContainerStatus;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Country;
use App\Models\Master\Currency;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\TariffType;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemurageController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Demurrage::class);

        $demurrage = Demurrage::where('company_id', Auth::user()->company_id)->get();
        $demurrages = Demurrage::where('company_id', Auth::user()->company_id)->filter(new ContainersIndexFilter(request()))->get();
        $countries = Country::orderBy('name')->get();

        return view('containers.demurrage.index', [
            'countries' => $countries,
            'items' => $demurrages->sortByDesc('id'),
            'demurrage' => $demurrage->sortByDesc('id'),
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__, Demurrage::class);
        $tariffTypes = TariffType::all();
        $countries = Country::orderBy('id')->get();
        $bounds = Bound::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $ports = [];
        $triffs = Triff::get();
        $currency = Currency::all();
        $terminals = [];
        $containerstatus = ContainerStatus::orderBy('id')->get();
        return view('containers.demurrage.create', [
            'terminals' => $terminals,
            'countries' => $countries,
            'bounds' => $bounds,
            'containersTypes' => $containersTypes,
            'ports' => $ports,
            'triffs' => $triffs,
            'currency' => $currency,
            'containerstatus' => $containerstatus,
            'tariffTypes' => $tariffTypes,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $demurrages = Demurrage::create([
            'country_id' => $request->input('country_id'),
            'terminal_id' => $request->input('terminal_id'),
            'container_type_id'=> $request->input('container_type_id'),
            'port_id' => $request->input('port_id'),
            'validity_from' => $request->input('validity_from'),
            'validity_to' => $request->input('validity_to'),
            'currency' => $request->input('currency'),
//            'bound_id'=> $request->input('bound_id'),
//            'is_storge' => $request->is_storge,
            'container_status' => $request->container_status,
            'tariff_id' => $request->input('tariff_id'),
            'company_id' => $user->company_id,
            'tariff_type_id' => $request->tariff_type_id,
        ]);
        $slabs = collect($request->period)->groupBy('container_type');
        foreach ($slabs as $slab) {
            $createdSlab = DemuragePeriodsSlabs::create([
                'demurage_id' => $demurrages->id,
                'container_type_id' => $demurrages->container_type_id,
            ]);
            foreach ($slab as $period) {
                Period::create([
                    'rate' => $period['rate'],
                    'period' => $period['period'],
                    'number_off_dayes' => $period['number_off_days'],
                    'slab_id' => $createdSlab->id,
                ]);
            }
        }
        return redirect()->route('demurrage.index')->with('success', trans('Demurrage.created'));
    }

    public function show($id)
    {
        $this->authorize(__FUNCTION__, Demurrage::class);
        $demurrages = Demurrage::find($id);
        $slabs = DemuragePeriodsSlabs::where('demurage_id', $id)->with('periods')->get();

        foreach ($slabs as $slab) {
            foreach ($slab->periods as $period) {
                $periodData = $period->toArray();
            }
        }
        // dd($slabs);
        return view('containers.demurrage.show', [
            'demurrages' => $demurrages,
            'slabs' => $slabs,
            'periodData' => $periodData,
        ]);
    }


    public function edit(Demurrage $demurrage)
    {
        $this->authorize(__FUNCTION__, Demurrage::class);
        $slabs = DemuragePeriodsSlabs::where('demurage_id', $demurrage->id)->with('periods')->get();
        $tariffTypes = TariffType::all();
        $countries = Country::orderBy('id')->get();
        $bounds = Bound::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::orderBy('id')->where('company_id', Auth::user()->company_id)->get();
        $triffs = Triff::get();
        $currency = Currency::all();
        $terminals = Terminals::where('company_id', Auth::user()->company_id)->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();
        
        return view('containers.demurrage.edit', [
            'demurrage' => $demurrage,
            'terminals' => $terminals,
            'countries' => $countries,
            'bounds' => $bounds,
            'containersTypes' => $containersTypes,
            'ports' => $ports,
            'triffs' => $triffs,
            'currency' => $currency,
            'containerstatus' => $containerstatus,
            'tariffTypes' => $tariffTypes,
            'slabs' => $slabs
        ]);
    }

    public function update(Request $request, Demurrage $demurrage)
    {
        $this->authorize(__FUNCTION__, Demurrage::class);

        // Load the associated periods for the demurrage model
        $demurrage->load('slabs');

        $slabsData = [
            'country_id' => $request->country_id,
            'terminal_id' => $request->terminal_id,
            'port_id' => $request->port_id,
            'container_type_id' => $request->container_type_id,
            'bound_id' => $request->bound_id,
            'currency' => $request->currency,
            'validity_from' => $request->validity_from,
            'validity_to' => $request->validity_to,
            'tariff_id' => $request->tariff_id,
            'is_storge' => $request->is_storge,
            'container_status' => $request->container_status,
        ];

        // Update the Demurrage model with the slabsData
        $demurrage->update($slabsData);

        // Create or update the slabs data
        $demurrage->slabs()->delete();
        $demurrage->periods()->delete();

        $slabs = collect($request->period)->groupBy('container_type');
        foreach ($slabs as $slab) {
            $containerTypeName = $slab->first()['container_type'];
            $containerTypeId = ContainersTypes::firstWhere('name','like',"%$containerTypeName%")->id;
            $createdSlab = $demurrage->slabs()->create([
                'container_type_id' => $containerTypeId
            ]);
            foreach ($slab as $period) {
                Period::create([
                    'rate' => $period['rate'],
                    'period' => $period['period'],
                    'number_off_dayes' => $period['number_off_days'],
                    'slab_id' => $createdSlab->id,
                    'demurrage_id' => $demurrage->id
                ]);
            }
        }

        return redirect()->route('demurrage.index')->with('success', trans('Demurrage.updated.success'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $demurrage = Demurrage::find($id);
        $slabs = DemuragePeriodsSlabs::where('demurage_id', $id)->with('periods')->get();
        foreach ($slabs as $slab) {
            foreach ($slab->periods as $period) {
                $period->delete();
            }
            $slab->delete();
        }
        $demurrage->delete();
        return redirect()->route('demurrage.index')->with('success', trans('Demurrage.deleted.success'));
    }
}
