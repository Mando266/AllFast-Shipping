<?php

namespace App\Http\Controllers\BlDraft;

use App\Filters\BlDraft\BlDraftIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Bl\BlDraftDetails;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Support\Facades\Auth;

class BlDraftController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,BlDraft::class);
        $blDrafts = BlDraft::filter(new BlDraftIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('blDetails','invoices','customer')->paginate(30);
        //dd($blDrafts);
        $exportbls = BlDraft::filter(new BlDraftIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('blDetails')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $customers = Customers::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $blDraftNo = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $ffw = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);})->get();
        session()->flash('bldarft',$exportbls);

        return view('bldraft.bldraft.index',[
            'items'=>$blDrafts,
            'blDraftNo'=>$blDraftNo,
            'ports'=>$ports,
            'customers'=>$customers,
            'voyages'=>$voyages,
            'ffw'=>$ffw,
        ]);
    }

    public function selectBooking()
    {
        $booking  = Booking::
        where('shipment_type', 'Export')->orderBy('id','desc')->
        where('booking_confirm',1)->where('has_bl',0)->
        where('company_id',Auth::user()->company_id)->with('customer')->get();
        $transhipments = Booking::orderBy('id','desc')->where('booking_confirm',1)->where('is_transhipment',1)->where('company_id',Auth::user()->company_id)->with('customer')->get();
        return view('bldraft.bldraft.selectBooking',[
            'booking'=>$booking,
            'transhipments'=>$transhipments
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,BlDraft::class);
        request()->validate([
            'booking_id' => ['required'],
        ]);
        $customershipper  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 1);
        })->with('CustomerRoles.role')->get();
        $customersConsignee  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 2);
        })->with('CustomerRoles.role')->get();
        $customersNotifiy = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->whereIn('role_id', [2,3]);
        })->with('CustomerRoles.role')->get();
        $booking = Booking::findOrFail(request('booking_id'));
        //dd($booking);
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
        $containers = Containers::where('company_id',Auth::user()->company_id)->where('status',2)->orderBy('id')->get();
        $voyages = Voyages::with('vessel','voyagePorts')->where('company_id',Auth::user()->company_id)->
        // whereHas('voyagePorts', function ($query) use($booking ){
        //     $query->where('port_from_name',$booking->load_port_id);
        // })->
        get();
        $booking_qyt = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id',000)->sum('qty');
        //dd($booking_qyt);
        $booking_containers = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id','!=',000)->with('container')->get();
        $oldbookingcontainers = Containers::where('company_id',Auth::user()->company_id)->get();
        $booking_no = Booking::where('id',request('booking_id'))->first();
        if(request('bldraft') == null){
            return view('bldraft.bldraft.create',[
            'booking_containers'=>$booking_containers,
            'customershipper'=>$customershipper,
            'customersConsignee'=>$customersConsignee,
            'customersNotifiy'=>$customersNotifiy,
            'booking'=>$booking,
            'equipmentTypes'=>$equipmentTypes,
            'ports'=>$ports,
            'containers'=>$containers,
            'voyages'=>$voyages,
            'booking_qyt'=>$booking_qyt,
            'oldbookingcontainers'=>$oldbookingcontainers,
            'booking_no'=>$booking_no,
        ]);
        }else{
            $blDraft = BlDraft::find(request('bldraft'));
            return view('bldraft.bldraft.create',[
                'booking_containers'=>$booking_containers,
                'blDraft'=>$blDraft,
                'customershipper'=>$customershipper,
                'customersConsignee'=>$customersConsignee,
                'customersNotifiy'=>$customersNotifiy,
                'booking'=>$booking,
                'equipmentTypes'=>$equipmentTypes,
                'ports'=>$ports,
                'containers'=>$containers,
                'voyages'=>$voyages,
                'booking_qyt'=>$booking_qyt,
                'oldbookingcontainers'=>$oldbookingcontainers,
                'booking_no'=>$booking_no,
            ]);
        }
    }

    public function store(Request $request)
    {
        if ($request->input('bl_status') == 0){
        $request->validate([
            'customer_id' => ['required'],
            // 'place_of_acceptence_id' => ['required'],
            'load_port_id' => ['required'],
            // 'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
            'voyage_id' => ['required'],
        ],[
            // 'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);
        }else{
            $request->validate([
                'customer_id' => ['required'],
                // 'place_of_acceptence_id' => ['required'],
                'load_port_id' => ['required'],
                // 'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
                'discharge_port_id' => ['required','different:load_port_id'],
                'voyage_id' => ['required'],
                'date_of_issue' =>['required'],
                'payment_kind' =>['required'],
                'bl_kind' =>['required'],
            ],[
                // 'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
                'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
            ]);
        }
        $booking = Booking::where('id',$request->booking_id)->with('bookingContainerDetails')->first();
        $uniqueContainers = array();
        foreach($request->blDraftdetails as $container){
            if(!in_array($container['container_id'],$uniqueContainers) ){
                if($container['container_id'] != "000"){
                    array_push($uniqueContainers,$container['container_id']);
                }
            }else{
                return redirect()->back()->with('error','Container Numbers Must be unique')->withInput($request->input());
            }
        }
        $user = Auth::user();
        if(isset($request->blDraft_id)){
            // Childs of Splitted BL Logic Here
            $ischild = 1;
            $hasChild = 0;
            $numOfChilds = BlDraft::where('parent_id',$request->blDraft_id)->count();
            $blDraftParent = BlDraft::where('id',$request->blDraft_id)->with('childs.blDetails','blDetails')->first();
            $qtyCount = count($blDraftParent->blDetails);
            // BlDraft forloop childs
            foreach($blDraftParent->childs as $child){
                $qtyCount = $qtyCount + count($child->blDetails);
            }
        if($request->input('movement') == 'FCL/FCL'){
            $qtyCount = $qtyCount + count($request->input('blDraftdetails'));
            if($qtyCount == $booking->bookingContainerDetails->sum('qty')){
                $blDraftParent->has_child = 0;
                $blDraftParent->save();
            }elseif($qtyCount > $booking->bookingContainerDetails->sum('qty')){
                return redirect()->back()->with('error','Containers is More than the booking containers')->withInput($request->input());
            }
        }
        }else{
            $ischild = 0;
            if($booking->bookingContainerDetails->sum('qty') > count($request->input('blDraftdetails'))){
                $hasChild = 1;
            }else{
                $hasChild = 0;
            }
        }
    
        if($ischild){
            $serialNum = $numOfChilds + 2;
            $serialChar = strtoupper(chr($serialNum + 64));
            $ref_no = $request->input('ref_no').$serialChar;
        }else{
            if($hasChild){
                $ref_no = $request->input('ref_no')."A";
            }else{
                $ref_no = $request->input('ref_no');
            }
        }
        $blDraft = BlDraft::create([
            'booking_id'=> $request->input('booking_id'),
            'company_id'=>$user->company_id,
            'has_child'=>$hasChild,
            'ref_no'=> $ref_no,
            'parent_id'=> $ischild ? $blDraftParent->id : '',
            'customer_id'=> $request->input('customer_id'),
            'customer_consignee_details'=> $request->input('customer_consignee_details'),
            'customer_notifiy_details'=> $request->input('customer_notifiy_details'),
            'additional_notify_details'=> $request->input('additional_notify_details'),
            'additional_notify_id'=> $request->input('additional_notify_id'),
            'customer_shipper_details'=> $request->input('customer_shipper_details'),
            'descripions'=> $request->input('descripions'),
            'customer_consignee_id'=> $request->input('customer_consignee_id'),
            'customer_notifiy_id'=> $request->input('customer_notifiy_id'),
            'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
            'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
            'load_port_id'=> $request->input('load_port_id'),
            'discharge_port_id'=> $request->input('discharge_port_id'),
            'equipment_type_id'=> $request->input('equipment_type_id'),
            'voyage_id'=> $request->input('voyage_id'),
            'number_of_original'=> $request->input('number_of_original'),
            'declerd_value'=> $request->input('declerd_value'),
            'bl_kind'=> $request->input('bl_kind'),
            'payment_kind'=> $request->input('payment_kind'),
            'movement'=> $request->input('movement'),
            'date_of_issue'=> $request->input('date_of_issue'),
            'bl_status'=> $request->input('bl_status'),
        ]);
        $booking = Booking::where('id',$request->input('booking_id'))->first();
        $booking->has_bl = 1;
        $booking->save();
        foreach($request->input('blDraftdetails',[]) as $blDraftdetails){
            BlDraftDetails::create([
                'bl_id'=>$blDraft->id,
                'container_id'=>$blDraftdetails['container_id'],
                'packs'=>$blDraftdetails['packs'],
                'pack_type'=>$blDraftdetails['pack_type'],
                'seal_no'=>$blDraftdetails['seal_no'],
                'description'=>$blDraftdetails['description'],
                'gross_weight'=>$blDraftdetails['gross_weight'],
                'net_weight'=>$blDraftdetails['net_weight'],
                'measurement'=>$blDraftdetails['measurement'],
            ]);
        }
        return redirect()->route('bldraft.index')->with('success',trans('Bl Draft.Created'));
    }

    public function show($id)
    {
        $blDraft = BlDraft::where('id',$id)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        if(Auth::user()->company_id == 2){
            return view('bldraft.bldraft.showMas',[
                'blDraft'=>$blDraft,
                'etdvoayege'=>$etdvoayege,
                ]);
        }
        return view('bldraft.bldraft.show',[
            'blDraft'=>$blDraft
            ]);
    }

    public function showCstar($id)
    {
        $blDraft = BlDraft::where('id',$id)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
            return view('bldraft.bldraft.showCstar',[
                'blDraft'=>$blDraft,
                'etdvoayege'=>$etdvoayege,
                ]);
    }


    public function edit(BlDraft $bldraft)
    {
        $this->authorize(__FUNCTION__,BlDraft::class);
        $bldraft = $bldraft->load('blDetails');
        $customershipper  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 1);
        })->with('CustomerRoles.role')->get();
        $customersConsignee  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 2);
        })->with('CustomerRoles.role')->get();
        $customersNotifiy = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->whereIn('role_id', [2,3]);
        })->with('CustomerRoles.role')->get();
        $booking = Booking::findOrFail(request('booking_id'));
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $containers = Containers::where('company_id',Auth::user()->company_id)->where('status',2)->orderBy('id')->get();
        $voyages    = Voyages::with('vessel','voyagePorts')->where('company_id',Auth::user()->company_id)
        // ->whereHas('voyagePorts', function ($query) use($booking ){
        //     $query->where('port_from_name',$booking->load_port_id);
        // })
        ->get();
        $booking_qyt = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id',000)->sum('qty');
        $booking_containers = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id','!=',000)->with('container')->get();
        $oldbookingcontainers = Containers::where('company_id',Auth::user()->company_id)->get();

        return view('bldraft.bldraft.edit',[
            'booking_containers'=>$booking_containers,
            'customershipper'=>$customershipper,
            'customersConsignee'=>$customersConsignee,
            'customersNotifiy'=>$customersNotifiy,
            'bldraft'=>$bldraft,
            'equipmentTypes'=>$equipmentTypes,
            'ports'=>$ports,
            'containers'=>$containers,
            'voyages'=>$voyages,
            'booking_qyt'=>$booking_qyt,
            'oldbookingcontainers'=>$oldbookingcontainers,
            'booking'=>$booking
        ]);
    }

    public function update(Request $request, BlDraft $bldraft)
    {
        if ($request->input('bl_status') == 1){
            $request->validate([
                'date_of_issue' =>['required'],
                'bl_kind' =>['required'],
                'date_of_issue' =>['required'],
            ]);
        }
        $user = Auth::user();
        $ReferanceNumber  = BlDraft::where('id','!=',$bldraft->id)->where('company_id',$user->company_id)->where('ref_no',$request->ref_no)->count();

        if($ReferanceNumber > 0){
            return back()->with('error','The BL Refrance Number Already Exists');
        }
        $this->authorize(__FUNCTION__,BlDraft::class);
        $bldraft = $bldraft ->load('blDetails');
        $inputs = request()->all();
        unset($inputs['blDraftdetails'],$inputs['_token'],$inputs['removed']);
        $bldraft->update($inputs);
        $bldraft->UpdateBlDetails($request->blDraftdetails);

        return redirect()->route('bldraft.index')->with('success',trans('BL Draft.Updated.Success'));
    }

    public function manifest($id)
    {
        $blDraft = BlDraft::where('id',$id)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        $etdfristvoayege = VoyagePorts::where('voyage_id',$blDraft->booking->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        return view('bldraft.bldraft.manifest',[
            'blDraft'=>$blDraft,
            'etdvoayege'=>$etdvoayege,
            'etdfristvoayege'=>$etdfristvoayege

            ]);
    }
    public function serviceManifest($id,$xml = false)
    {
        $blDraft = BlDraft::where('id',$id)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        $etdfristvoayege = VoyagePorts::where('voyage_id',$blDraft->booking->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();

        return view('bldraft.bldraft.serviceManifest',[
            'blDraft'=>$blDraft,
            'voyage'=>request()->voyage ?? '',
            'loadPort'=>request()->loadPort ?? null,
            'dischargePort'=>request()->dischargePort ?? null,
            'xml'=>$xml,
            'etdvoayege'=>$etdvoayege,
            'etdfristvoayege'=>$etdfristvoayege
            ]);
    }




    public function destroy($id)
    {
        $bldraft = BlDraft::find($id);
        $booking = Booking::where('id',$bldraft->booking_id)->first();
        $booking->has_bl = 0;
        $booking->save();
        BlDraftDetails::where('bl_id',$id)->delete();
        $bldraft->delete();
        return back()->with('success',trans('BlDraft.Deleted.Success'));
    }
}
