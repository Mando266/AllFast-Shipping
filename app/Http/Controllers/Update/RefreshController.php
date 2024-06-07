<?php

namespace App\Http\Controllers\Update;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use Illuminate\Http\Request;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\Ports;
use App\Services\BldraftService;
use Illuminate\Support\Facades\Auth;
use App\Models\Quotations\Quotation;


class RefreshController extends Controller
{

    public function updateQuotation()
    {
        $quotations = Quotation::all();
        foreach($quotations as $quotation){
            $portOfLoad = Ports::find($quotation->load_port_id);
            $portOfDischarge = Ports::find($quotation->discharge_port_id);
            $shipment_type = '';
            //check if port of load in egypt to make shipment type export or import
            if($portOfLoad->country_id == 61){
                $shipment_type = 'Export';
            }
            if($portOfDischarge->country_id == 61){
                $shipment_type = 'Import';
            }
            $quotation->update(['shipment_type'=> $shipment_type]);
        }
        dd("Done");
    }
    public function updateContainers()
    {
        $movements = Movements::orderBy('movement_date','desc')->with('movementcode.containerstock')->get();

            $new = $movements;
            $new = $new->groupBy('movement_date');

            foreach($new as $key => $move){
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();

            $movements = $new;
            $filteredData = $movements->unique('container_id');
            foreach($filteredData as $key => $move){
                // Get All movements and sort it and get the last movement before this movement
                $tempMovements = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->with('movementcode.containerstock')->get();

                $new = $tempMovements;
                $new = $new->groupBy('movement_date');

                foreach($new as $k => $move){
                    $move = $move->sortByDesc('movementcode.sequence');
                    $new[$k] = $move;
                }
                $new = $new->collapse();

                $tempMovements = $new;
                $lastMove = $tempMovements->first();
                $container = Containers::where('id',$lastMove->container_id)->first();
                $activity_location = Ports::where('code',$lastMove->port_location_id)->where('company_id',optional(Auth::user())->company->id)->pluck('id')->first();
                if($activity_location != null){
                    $container->update(['activity_location_id'=>$activity_location]);
                }
                // End Get All movements and sort it and get the last movement before this movement
                if($lastMove->container_status == 1){
                    $container->update(['status'=>$lastMove->container_status]);
                }elseif($lastMove->container_status == 2 && $lastMove->movementcode->containerstock->code == "NOT AVAILABLE"){
                    $container->update(['status'=>1]);
                }else{
                    $container->update(['status'=>$lastMove->container_status]);
                }

            }
        // $movements = Movements::where('container_status',2)->orderbyDesc('created_at')->groupBy('container_id')->get()->pluck('container_id');
        // $movements = Movements::where('container_status',1)->orderbyDesc('created_at')->first();
        return redirect()->route('movements.index')->with('success',"CONTAINERS UPDATED SUCCESSFULLY");
    }
    public function updateBookingContainers($id = null,BldraftService $bldraftService)
    {
        if($id == null){
            $bldrafts = BlDraft::where('company_id',optional(Auth::user())->company->id)->with('blDetails')->get();
            foreach($bldrafts as $bldraft){
                $qty = 0;
                $booking = Booking::where('id',$bldraft->booking_id)->with('bookingContainerDetails')->first();
                foreach($booking->bookingContainerDetails as $detail){
                    $qty += $detail->qty;
                }
                $bldraftService->syncBldraftWithBooking($bldraft,$booking,$qty);
            }
        }else{
            $bldraft = BlDraft::where('id',$id)->with('blDetails')->first();
            $qty = 0;
            $booking = Booking::where('id',$bldraft->booking_id)->with('bookingContainerDetails')->first();
                foreach($booking->bookingContainerDetails as $detail){
                    $qty += $detail->qty;
                }
            $bldraftService->syncBldraftWithBooking($bldraft,$booking,$qty);
            return redirect()->route('bldraft.index')->with('success',"BOOKING UPDATED SUCCESSFULLY");
        }

        // die();
        return redirect()->route('bldraft.index')->with('success',"BOOKINGS UPDATED SUCCESSFULLY");
    }
}
