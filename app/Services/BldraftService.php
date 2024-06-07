<?php

namespace App\Services;

use App\Models\Bl\BlDraft;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;

class BldraftService {

 

    public function syncBldraftWithBooking(BlDraft $bldraft,Booking $booking,$qty)

    {
        if($qty == $bldraft->blDetails->count()){
            // All containers verified in Bl so we can sync to booking
            foreach($booking->bookingContainerDetails as $detail){
                if($detail->qty == 1){
                    $i = 0;
                    foreach($bldraft->blDetails as $key => $blDetail){
                        if($i < $detail->qty){
                            // dump('will update');
                            $haz = $blDetail->packs . " ".$blDetail->pack_type . " ON " . $qty . " Container, GW " 
                            . $blDetail->gross_weight . " KGS, NW " . $blDetail->net_weight . " KGS, ".$blDetail->description;
                            $detail->update([
                                'container_id'=>$blDetail->container_id,
                                'seal_no'=>$blDetail->seal_no,
                                'haz'=>$haz,
                            ]);
                            $bldraft->blDetails->forget($key);
                            $i++;
                        }else{
                            // dump('will not update');
                        }
                    }
                }else{
                    $qty = $detail->qty;
                    $activityLocation = $detail->activity_location_id;
                    $detail->delete();
                    $i = 0;
                    foreach($bldraft->blDetails as $key => $blDetail){
                        if($i < $detail->qty){
                            $haz = $blDetail->packs . " ".$blDetail->pack_type . " ON " . $qty . " Container, GW " 
                            . $blDetail->gross_weight . " KGS, NW " . $blDetail->net_weight . " KGS, ".$blDetail->description;
                            BookingContainerDetails::create([
                                'seal_no'=>$blDetail->seal_no,
                                'qty'=>1,
                                'container_id'=>$blDetail->container_id,
                                'booking_id'=>$booking->id,
                                'container_type'=>$booking->equipment_type_id,
                                'haz'=>$haz,
                                'activity_location_id'=>$activityLocation,
                            ]);
                            $bldraft->blDetails->forget($key);
                            $i++;
                        }
                    }
                }
            }
        }

    }

}