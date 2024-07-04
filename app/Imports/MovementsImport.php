<?php

namespace App\Imports;

use Illuminate\Support\Facades\Session;
use App\Models\Containers\Movements;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainerStatus;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Vessels;
use App\MovementImportErrors;
use Exception;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Voyages\Voyages;
use App\Models\Voyages\Legs;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;


class MovementsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // CHECK if nll
        $a = collect($row);
        $z = $a->filter(fn($v)=>$v != null)->toArray();
        if(empty($z))
            return null;

        $containerId = $row['container_id'];
        $containertype = $row['container_type_id'];
        $voyage = $row['voyage_id'];
        $leg = $row['leg'];
        $activitylocation = $row['port_location_id'];
        $pol = $row['pol_id'];
        $pod = $row['pod_id'];
        $booking = $row['booking_no'];

        $row['container_id'] = Containers::where('company_id',Auth::user()->company_id)->where('code',$row['container_id'])->pluck('id')->first();
        $row['leg'] = Legs::where('name',$row['leg'])->pluck('id')->first();
        $row['voyage_id'] = Voyages::where('company_id',Auth::user()->company_id)->where('voyage_no',$row['voyage_id'])->where('leg_id',$row['leg'])->pluck('id')->first();
        $row['port_location_id'] = Ports::where('company_id',Auth::user()->company_id)->where('code',$row['port_location_id'])->pluck('id')->first();
        $row['pol_id'] = Ports::where('company_id',Auth::user()->company_id)->where('code',$row['pol_id'])->pluck('id')->first();
        $row['pod_id'] = Ports::where('company_id',Auth::user()->company_id)->where('code',$row['pod_id'])->pluck('id')->first();

        // Validation
        if($row['port_location_id'] == null || $row['movement_date'] == null || $row['movement_id'] == null){
            return Session::flash('message', "This Container Number: {$containerId} Must have Movement Code and Activity location and Movement Date");
        }
        if(!$row['voyage_id']){
            
            return session()->flash('message',"This Voyage Number: {$voyage} or leg: {$leg} Not found");
        }

        if(!$row['container_id']){
            
            return session()->flash('message',"This container Number: {$containerId} Not found ");
        }
        if(!$row['container_type_id']){
            
            return session()->flash('message',"This Container Type: {$containertype} Not found ");
        } 

        if(!$row['port_location_id']){
            
            return session()->flash('message',"This Activity Location Code: {$activitylocation} Not found");
        }

        if(!$row['pol_id']){
            
            return session()->flash('message',"This POL Code: {$pol} Not found");
        }

        if(!$row['pod_id']){
            
            return session()->flash('message',"This POD Code: {$pod} Not found");
        }
        if(!$row['booking_no']){
            
            return session()->flash('message',"This Booking NO: {$booking} Not found");
        }

        // try {
        //     // code that triggers a pdo exception
        //     $date = Date::excelToDateTimeObject($row['movement_date']);
        //     $dateConvertion = $date->format('Y-m-d');
        //   } catch (Exception $e) {
        //     $dateConvertion = $row['movement_date'];
        //   }
        //   $row['movement_date'] = $dateConvertion;
        try {
            $date = Date::excelToDateTimeObject($row['movement_date']);
            $dateConvertion = $date->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            $dateConvertion = $row['movement_date'];
        }
        $row['movement_date'] = $dateConvertion;
        
        // Get All movements and sort it and get the last movement before this movement 

        $movements = Movements::where('container_id',$row['container_id'])->orderBy('movement_date','desc')->with('movementcode')->get();
        $new = $movements;
        $new = $new->groupBy('movement_date');
        
        foreach($new as $key => $move){
            $move = $move->sortByDesc('movementcode.sequence');
            $new[$key] = $move;
        }
        $new = $new->collapse();
        
        $movements = $new;
        $lastMove = $movements->where('movement_date','<=',$row['movement_date'])->first();
        // End Get All movements and sort it and get the last movement before this movement
        
        $lastMoveCode = ContainersMovement::where('id',$lastMove)->pluck('code')->first();
        $nextMoves = ContainersMovement::where('id',$lastMove)->pluck('next_move')->first();
        $nextMoves = explode(', ',$nextMoves);
        $movementCode = $row['movement_id'];
        
        // dd($lastMoveCode);
        $row['movement_id'] =  ContainersMovement::where('code',$row['movement_id'])->pluck('id')->first();
        $row['container_type_id'] = ContainersTypes::where('name',$row['container_type_id'])->pluck('id')->first();
        $row['container_status'] = ContainerStatus::where('name',$row['container_status'])->pluck('id')->first();
        $row['vessel_id'] = Vessels::where('name',$row['vessel_id'])->where('company_id',Auth::user()->company_id)->pluck('id')->first();
        $row['booking_agent_id'] = Agents::where('name',$row['booking_agent_id'])->pluck('id')->first();
        $row['import_agent'] = Agents::where('name',$row['import_agent'])->pluck('id')->first();
        $row['booking_no'] = Booking::where('ref_no',$row['booking_no'])->pluck('id')->first();
        
        if(!$row['movement_id']){
            
            return session()->flash('message',"This Movement Code: {$movementCode} Not found ");
        } 
        
        if($movements->first() != null){
            $moveType = $movements->first()->container_type_id;
            // Check same move type
            if(  $row['container_type_id'] != $moveType){
            return session()->flash('message',"This Container Type: {$containertype} Must be Same as Movements Container Type");
            }
        }

        // Check same move type
        if(  $row['vessel_id'] == null){
            return session()->flash('message',"You Must Enter a Vessel Name");
        }

        $movementdublicate  = Movements::where('container_id',$row['container_id'])->where('movement_id',$row['movement_id'])->where('movement_date',$row['movement_date'])->first();
        
        if($containerId == null){
            return Session::flash('stauts', 'Cannot Container Number be Null please check Excel Sheet');
        }

        if($movementdublicate != null){
            return Session::flash('message', 'This Container Number: '.$containerId.' With This Movement Code: '.$movementCode.' Already Exists!');
        }
        $user = Auth::user();
        if(in_array($movementCode,$nextMoves)){
            $movement = Movements::create([
                'container_id' => $row['container_id'],
                'container_type_id' => $row['container_type_id'],
                'movement_id' => $row['movement_id'],
                'movement_date' => $row['movement_date'],
                'port_location_id' => $row['port_location_id'],
                'pol_id' => $row['pol_id'],
                'pod_id' => $row['pod_id'],
                'vessel_id' => $row['vessel_id'],
                'voyage_id' => $row['voyage_id'],
                'terminal_id' => $row['terminal_id'],
                'booking_no' => $row['booking_no'],
                'bl_no' => $row['bl_no'],
                'remarkes' => $row['remarkes'],
                'transshipment_port_id' => $row['transshipment_port_id'],
                'booking_agent_id' => $row['booking_agent_id'],
                'free_time' => $row['free_time'],
                'container_status' => $row['container_status'],
                'import_agent' => $row['import_agent'],
                'free_time_origin' => $row['free_time_origin'],
            ]);
            $movement->company_id = $user->company_id;
            $movement->save();
             
        }elseif($nextMoves[0] == ""){
            $movement = Movements::create([
                'container_id' => $row['container_id'],
                'container_type_id' => $row['container_type_id'],
                'movement_id' => $row['movement_id'],
                'movement_date' => $row['movement_date'],
                'port_location_id' => $row['port_location_id'],
                'pol_id' => $row['pol_id'],
                'pod_id' => $row['pod_id'],
                'vessel_id' => $row['vessel_id'],
                'voyage_id' => $row['voyage_id'],
                'terminal_id' => $row['terminal_id'],
                'booking_no' => $row['booking_no'],
                'bl_no' => $row['bl_no'],
                'remarkes' => $row['remarkes'],
                'transshipment_port_id' => $row['transshipment_port_id'],
                'booking_agent_id' => $row['booking_agent_id'],
                'free_time' => $row['free_time'],
                'container_status' => $row['container_status'],
                'import_agent' => $row['import_agent'],
                'free_time_origin' => $row['free_time_origin'],
            ]);
            $movement->company_id = $user->company_id;
            $movement->save();
        }else{
   
            MovementImportErrors::create([
                'container_id' => $containerId,
                'date' => $row['movement_date'],
                'error_code' => $movementCode,
                'allowed_code' => implode(", ", $nextMoves)
            ]);
            return Session::flash('message', 'Error in Allowed Next Movement');
        }

        return $movement;
    }


    
}
