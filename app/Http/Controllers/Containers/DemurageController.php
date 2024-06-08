<?php

namespace App\Http\Controllers\Containers;

use App\Filters\Containers\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Containers\Bound;
use App\Models\Containers\DemuragePeriodsSlabs;
use App\Models\Containers\DemurageContainerType ;
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

        // Create the demurrage entry
        $demurrage = Demurrage::create([
            'country_id' => $request->input('country_id'),
            'terminal_id' => $request->input('terminal_id'),
            'port_id' => $request->input('port_id'),
            'validity_from' => $request->input('validity_from'),
            'validity_to' => $request->input('validity_to'),
            'currency' => $request->input('currency'),
            'container_status' => $request->input('container_status'),
            'tariff_id' => $request->input('tariff_id'),
            'company_id' => $user->company_id,
            'tariff_type_id' => $request->input('tariff_type_id'),
        ]);

        // Iterate over each container type
        foreach ($request->input('container_types') as $containerTypeId => $containerTypeData) {
            // Check if periods data is set and is an array
            if (!isset($containerTypeData['periods']) || !is_array($containerTypeData['periods'])) {
                // Handle the error, e.g., skip this container type or throw an exception
                continue;
            }

            // Create a new DemurageContainerType entry
            $createdContainerTypeSlab = DemurageContainerType::create([
                'demurage_id' => $demurrage->id,
                'container_type_id' => $containerTypeId,
            ]);

            // Create associated DemuragePeriodsSlabs entries
            foreach ($containerTypeData['periods'] as $period) {
                DemuragePeriodsSlabs::create([
                    'rate' => $period['rate'],
                    'period' => $period['period'],
                    'number_off_dayes' => $period['days'],
                    'container_type_id' => $containerTypeId,
                    'demurrage_container_id' => $createdContainerTypeSlab->id,
                ]);
            }
        }

        return redirect()->route('demurrage.index')->with('success', trans('Demurrage.created'));
    }




    public function show($id)
    {
        $this->authorize(__FUNCTION__, Demurrage::class);

        $demurrages = Demurrage::find($id);
        $slabs = DemurageContainerType::where('demurage_id', $id)->with('periods')->get();
        $periodData = [];

        foreach ($slabs as $slab) {
            foreach ($slab->periods as $period) {
                $periodData[] = $period->toArray();
            }
        }

        $tariffTypes = TariffType::all();

        return view('containers.demurrage.show', [
            'demurrages' => $demurrages,
            'slabs' => $slabs,
            'periodData' => $periodData,
            'tariffTypes' => $tariffTypes,
        ]);
    }




    public function edit(Demurrage $demurrage)
    {
        $this->authorize(__FUNCTION__, Demurrage::class);
        $slabs = DemurageContainerType::where('demurage_id', $demurrage->id)->with('periods')->get();
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

        // Define the demurrage data that can be updated
        $demurrageData = [
            'country_id' => $request->country_id,
            'terminal_id' => $request->terminal_id,
            'port_id' => $request->port_id,
            'bound_id' => $request->bound_id,
            'currency' => $request->currency,
            'validity_from' => $request->validity_from,
            'validity_to' => $request->validity_to,
            'tariff_id' => $request->tariff_id,
            'is_storge' => $request->is_storge,
            'container_status' => $request->container_status,
        ];

        // Update the Demurrage model with the data
        $demurrage->update($demurrageData);

        // Track existing container types for comparison
        $existingContainerTypes = $demurrage->slabs->pluck('container_type_id')->toArray();

        // Iterate over each container type and its periods to create or update slabs and periods
        foreach ($request->container_types as $containerTypeId => $containerTypeData) {
            $createdSlab = DemurageContainerType::updateOrCreate(
                ['demurage_id' => $demurrage->id, 'container_type_id' => $containerTypeData['id']],
                ['container_type_id' => $containerTypeData['id']]
            );

            // Delete old periods related to the current slab
            DemuragePeriodsSlabs::where('demurrage_container_id', $createdSlab->id)->delete();

            if (isset($containerTypeData['periods'])) {
                foreach ($containerTypeData['periods'] as $period) {
                    DemuragePeriodsSlabs::create([
                        'rate' => $period['rate'],
                        'period' => $period['period'],
                        'number_off_dayes' => $period['days'],
                        'container_type_id' => $containerTypeData['id'],
                        'demurrage_container_id' => $createdSlab->id,
                    ]);
                }
            }
        }

        // Delete slabs for container types that were removed
        $newContainerTypes = array_column($request->container_types, 'id');
        $containerTypesToDelete = array_diff($existingContainerTypes, $newContainerTypes);

        foreach ($containerTypesToDelete as $deletedContainerTypeId) {
            $slab = DemurageContainerType::where('demurage_id', $demurrage->id)->where('container_type_id', $deletedContainerTypeId)->first();
            if ($slab) {
                DemuragePeriodsSlabs::where('demurrage_container_id', $slab->id)->delete();
                $slab->delete();
            }
        }

        return redirect('/containers/demurrage')->with('success', trans('Demurrage.updated.success'));
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
        $slabs = DemurageContainerType::where('demurage_id', $id)->with('periods')->get();
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
