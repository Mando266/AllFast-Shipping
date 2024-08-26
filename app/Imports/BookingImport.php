<?php

namespace App\Imports;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use Illuminate\Support\Facades\Session;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class BookingImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // CHECK if null
        $a = collect($row);
        $z = $a->filter(fn($v) => $v != null)->toArray();
        if(empty($z))
            return null;

        $containerId = $row['container_id'];
        $row['booking_id'] = Booking::where('company_id',Auth::user()->company_id)->where('ref_no',$row['booking_id'])->pluck('id')->first();
        $row['container_id'] = Containers::where('company_id',Auth::user()->company_id)->where('code',$row['container_id'])->pluck('id')->first();
        $row['activity_location'] = Ports::where('company_id',Auth::user()->company_id)->where('code',$row['activity_location'])->pluck('id')->first();
        $row['container_type'] = ContainersTypes::where('name',$row['container_type'])->pluck('id')->first();
        $containerDuplicate = BookingContainerDetails::where('booking_id',$row['booking_id'])->where('container_id',$row['container_id'])->count();
        // Validation
        if(!$row['container_id']){
            return session()->flash('message',"This Container Number: {$containerId} Not found and containers before this container imported successfully");
        }
        if($containerDuplicate > 0 ){
            return Session::flash('message', "This Container Number: {$containerId} Already in this booking and containers before this container imported successfully");
        }
        if($row['booking_id'] == null){
            return Session::flash('message', "You Must Enter booking No");
        }
        if($row['activity_location'] == null){
            return Session::flash('message', "You Must enter Container Location");
        }
        
        $bookingDetail = BookingContainerDetails::create([
            'booking_id' => $row['booking_id'],
            'seal_no' => $row['seal_no'],
            'qty' => $row['qty'],
            'activity_location_id' => $row['activity_location'],
            'container_id' => $row['container_id'],
            'container_type' => $row['container_type'],
            'haz' => $row['haz'],
            'weight' => $row['weight'],
            'net_weight' => $row['net_weight'],
            'packs' => $row['packs'],
            'pack_type' => $row['pack_type'],
            'description' => $row['description'],
        ]);

        return $bookingDetail;
    }


    
}