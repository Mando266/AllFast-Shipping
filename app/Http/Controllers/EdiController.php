<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EdiRecord;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Containers\Movements;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use App\Models\Booking\Booking;
use App\Models\Master\Ports;
use App\Helpers\EdiParser;
use Illuminate\Support\Facades\Log;

class EdiController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'edi_file' => 'required|file|mimes:txt',
        ]);
    
        $file = $request->file('edi_file');
        $filePath = $file->getRealPath();
    
        try {
            $records = EdiParser::parse($filePath);
            // dd($records);
    
            foreach ($records as $record) {
                // Retrieve the container based on container_no
                $container = Containers::where('code', $record['container_no'])->first();
                $containerId = $container ? $container->id : Containers::first()->id;
    
                // Retrieve the voyage based on voyage_number
                $voyage = Voyages::where('voyage_no', $record['voyage_number'])->first();
                $voyageId = $voyage ? $voyage->id : Voyages::first()->id;
    
                // Find or create the vessel by ship name
                $vessel = Vessels::where('name', $record['ship_name'])->first();
                $vesselId = $vessel ? $vessel->id : Vessels::first()->id;
    
                // Find the booking by booking number (corresponding to ref_no in Booking table)
                $booking = Booking::where('ref_no', $record['booking_number'])->first();
                $bookingId = $booking ? $booking->id : Booking::first()->id;
    
               
    
                // Retrieve ports based on port codes
                $pol = Ports::where('code', $record['pol'])->first();
                $polId = $pol ? $pol->id : null;
    
                $pod = Ports::where('code', $record['pod'])->first();
                $podId = $pod ? $pod->id : null;
    
                $activityLocation = Ports::where('code', $record['activity_location'])->first();
                $activityLocationId = $activityLocation ? $activityLocation->id : null;
    
                // Retrieve the movement type
                $movementType = ContainersMovement::where('code', $record['movement_type'])->first();
                $movementTypeId = $movementType ? $movementType->id : ContainersMovement::first()->id;
    
                if (!$movementType) {
                    Log::error('Movement type not found, using fallback:', [
                        'movement_type' => $record['movement_type'] ?? 'N/A',
                    ]);
                }
    
                // Insert data into the movements table
                Movements::create([
                    'container_id' => $containerId,
                    'container_type_id' => $container->container_type_id ?? Containers::first()->container_type_id,
                    'movement_id' => $movementTypeId,
                    'movement_date' => $record['actual_date'] ?? now(),
                    'port_location_id' => 00,
                    'pol_id' => $polId,
                    'pod_id' => $podId,
                    'vessel_id' => $vesselId,
                    'voyage_id' => $voyageId,
                    'terminal_id' => $record['terminal_id'] ?? null,
                    'booking_no' => $bookingId,
                    'bl_no' => $bookingId ?? null,
                    'remarkes' => $record['goods_description'] ?? null,
                    'transshipment_port_id' => $record['transshipment_port_id'] ?? null,
                    'booking_agent_id' => $record['booking_agent_id'] ?? null,
                    'free_time' => $record['free_time'] ?? null,
                    'container_status' => $record['container_status'] ?? null,
                    'import_agent' => $record['import_agent'] ?? null,
                    'free_time_origin' => $record['free_time_origin'] ?? null,
                ]);
    
                Log::debug('Movement record saved:', [
                    'container_id' => $containerId,
                    'voyage_id' => $voyageId,
                    'vessel_id' => $vesselId,
                    'movement_id' => $movementId,
                    'booking_id' => $bookingId,
                ]);
            }
    
            return redirect()->route('edi.index')->with('success', 'EDI data parsed and saved successfully.');
    
        } catch (\Exception $e) {
            Log::error('Error parsing EDI file:', ['message' => $e->getMessage()]);
            return back()->withErrors(['edi_file' => 'Failed to parse EDI file: ' . $e->getMessage()]);
        }
    }
    

        
    
    
    

    public function index(Request $request)
    {
        $query = EdiRecord::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('arrival_date', [$request->from_date, $request->to_date]);
        }

        // Use paginate method instead of get
        $ediRecords = $query->paginate(10);

        return view('edi.index', compact('ediRecords'));
    }

}
