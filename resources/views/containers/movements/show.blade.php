@extends('layouts.app')
@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('movements.index')}}">Movements</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Movement Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">

                                @permission('Movements-List')
                                @if (Auth::user()->id != 18)
                                <form class="export-form" action="{{ route('export') }}" method="post">
                                        @csrf
                                    <input type="hidden" name="items" value="">
                                        <button class="btn btn-warning" type="submit">Export</button>
                                </form>
                                @endif
                                @endpermission
                                @permission('Movements-Create')
                                    <a href="{{route('movements.create',['container_id'=>$containers->id])}}" class="btn btn-primary">Add New Movement</a>
                                @endpermission
                            </div>

                        </div>
                </div>
            </br>
            <h5><span style='color:#1b55e2';>Container No / Type:</span> {{$containers->code}} / {{{optional($containers->containersTypes)->name}}}</h5>
            </br>
                <!-- <form>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{request()->input('movement_date')}}">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" id="BLNoInput" name="bl_no" value="{{request()->input('bl_no')}}"
                            placeholder="BL No" autocomplete="off">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" id="BookingNoInput" name="booking_no" value="{{request()->input('booking_no')}}"
                            placeholder="Booking No" autocomplete="off">
                        </div>
                        <div class="col-md-12 text-center">
                            <button  type="submit" class="btn btn-success mt-3">Search</button>
                            <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                        </div>
                    </div>
                </form> -->
                <?php

use App\Models\Containers\Movements;

if(request()->input('container_id') != null){

                    $container_id = request()->input('container_id');
                    if(is_array($container_id)){
                        $container_id = $container_id[0];
                    }
                }elseif(request()->input('bl_no') != null){
                    $container_id = Movements::where('bl_no',request()->input('bl_no'))->pluck('container_id')->first();

                }elseif($id != null){

                    $container_id = $id;
                    }?>

                {{-- <form action="{{ route('movements.show',['movement'=>$container_id]) }}" method="GET" enctype="multipart/form-data">
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="countryInput">Select Triff</label>
                                <select class="selectpicker form-control" id="Triff_id" data-live-search="true" name="Triff_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($demurrages as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('Triff_id',request()->input('Triff_id')) ? 'selected':''}}>{{{optional($item->country)->name}}} {{{optional($item->ports)->code}}} {{{optional($item->bound)->name}}} {{{optional($item->containersType)->name}}} {{$item->validity_from}}</option>
                                    @endforeach
                                </select>
                                @error('Triff_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="countryInput"> Till Date</label>
                                    <input type="date" class="form-control" id="TillDate" name="TillDate" min="2018-01-01" value="{{request()->input('TillDate')}}">
                            </div>
                            <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Calculate</button>
                            </div>
                    </div>
                </form> --}}

                    @if($lastDCHF != null && $tillDate != null)
                    <div class="row">
                        <div class="col-md-3">
                            <h4>Till Date</h4>
                            <h4>Price</h4>
                        </div>
                        <div class="col-md-3">
                            <h5>{{$tillDate}}</h5>
                            <?php

                                $total = 0;
                                $freetime = $lastDCHF->free_time;
                                $remainingFreeTime = $freetime;
                                $periodtimeTotal = 0;
                                foreach($periods as $period){
                                    if($period->period != 'Thereafter'){$periodtimeTotal += $period->number_off_dayes;}


                                }
                                $thereafter = false;
                                $remainingDays = (strtotime($tillDate) - strtotime($lastDCHF->movement_date)) / 86400 + 1;
                                $totalPrice = 0;
                                if(sizeof($periods) > 0){
                                        if($freetime >= $periodtimeTotal){
                                            $thereafter = true;
                                        }
                                        if($thereafter){

                                            foreach($periods as $period){
                                                if($period->period == 'Thereafter'){
                                                    $rate = $period->rate;
                                                }
                                            }
                                            $remainingDays = $remainingDays - $freetime;
                                            if($remainingDays < 0){$remainingDays = 0;}

                                            $total = $remainingDays * $rate;
                                            $thereafter = false;
                                        }else{


                                        foreach($periods as $period){

                                            if($period->period == 'free time' && $remainingDays > 0){
                                                if($freetime > $period->number_off_dayes){
                                                    $remainingFreeTime = $freetime - $period->number_off_dayes;
                                                }
                                                $total = 0;
                                                $remainingDays = $remainingDays - $period->number_off_dayes;
                                                if($remainingDays < 0){$remainingDays = 0;}
                                            }elseif($period->period == 'Thereafter' && $remainingDays > 0){
                                                if($remainingFreeTime == 0){
                                                    $price = $remainingDays * $period->rate;
                                                    $total += $price;
                                                    $remainingDays = 0;
                                                }elseif($remainingFreeTime > 0){
                                                    $price = ($remainingDays - $remainingFreeTime) * $period->rate;
                                                    if($price < 0){
                                                        $price = 0;
                                                    }
                                                    $total += $price;
                                                    $remainingFreeTime = 0;
                                                    $remainingDays = 0;
                                                }else{
                                                    $remainingFreeTime = 0;
                                                }

                                            }elseif($remainingDays > 0){
                                                if($remainingFreeTime == 0){
                                                    if($remainingDays > $period->number_off_dayes){
                                                        $price = $period->rate * $period->number_off_dayes;
                                                        $total += $price;
                                                        $remainingDays = $remainingDays - $period->number_off_dayes;
                                                    }else{
                                                        $price = $period->rate * $remainingDays;
                                                        $total += $price;
                                                        $remainingDays = 0;
                                                    }
                                                }elseif($remainingFreeTime > 0){
                                                    if($remainingDays > $remainingFreeTime){
                                                        $remainingDays -= $remainingFreeTime;
                                                        $remainingFreeTime = 0;
                                                    }elseif($remainingDays < $remainingFreeTime){
                                                        $remainingFreeTime = 0;
                                                        $remainingDays = 0;
                                                    }else{
                                                        $remainingDays = 0;
                                                        $remainingFreeTime = 0;
                                                    }
                                                }else{
                                                    $remainingFreeTime = 0;
                                                }
                                            }
                                        }
                                    }

                                }else{
                                    $total = 0;
                                }

                                $totalPrice += $total;
                            ?>
                            <h5>{{$total}}</h5>
                        </div>
                    </div>
                    @endif


                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>Movement</th>
                                        <th>Movement Date</th>
                                        <th>ACTIVITY LOCATION</th>
                                        <th>Pol</th>
                                        <th>Pod</th>
                                        <th>VSL/VOY</th>
                                        <th>BOOKING</th>
                                        <th>BL No</th>
                                        <th>free time destination</th>
                                        <th>import agent</th>
                                        <th>booking agent</th>
                                        <th>REMARKS</th>
                                        {{-- <th>Dentention</th> --}}

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $dchfDate = date('Y-m-d');
                                    $hasRCVC = false;
                                    $temp = 0;

                                ?>

                                    @if($movementId == true)

                                        @if($movementsArray == false)
                                        <tr>
                                            <td>{{{optional($items->movementcode)->code}}}</td>
                                            <td>{{$items->movement_date}}</td>
                                            <td>{{optional($items->activitylocation)->code}}</td>
                                            <td>{{optional($items->pol)->code}}</td>
                                            <td>{{optional($items->pod)->code}}</td>
                                            <td>{{{optional($items->vessels)->name}}} {{optional($items->voyage)->voyage_no}}</td>
                                            <td>{{optional($items->booking)->ref_no}}</td>
                                            <td>{{$items->bl_no}}</td>
                                            <td>{{$items->free_time}}</td>
                                            <td>{{{optional($items->importAgent)->name}}}</td>
                                            <td>{{{optional($items->bookingAgent)->name}}}</td>
                                            <td>{{$items->remarkes}}</td>
                                            <td></td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Edit')
                                                    <li>
                                                            <a href="{{route('movements.edit',['movement'=>$items->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                <i class="far fa-edit text-success"></i>
                                                            </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movements.destroy',['movement'=>$items->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                        @else
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                        @endif
                                    @else
                                        @forelse ($items as $item)
                                        <tr>
                                            <td>{{{optional($item->movementcode)->code}}}</td>
                                            <td>{{$item->movement_date}}</td>
                                            <td>{{optional($item->activitylocation)->code}}</td>
                                            <td>{{optional($item->pol)->code}}</td>
                                            <td>{{optional($item->pod)->code}}</td>
                                            <td>{{{optional($item->vessels)->name}}} {{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{optional($item->booking)->ref_no}}</td>
                                            <td>{{$item->bl_no}}</td>
                                            <td>{{$item->free_time}}</td>
                                            <td>{{{optional($item->importAgent)->name}}}</td>
                                            <td>{{{optional($item->bookingAgent)->name}}}</td>
                                            <td>{{$item->remarkes}}</td>

                                            {{-- @if( ($item->bl_no !=null || $item->booking_no !=null)&& optional($item->movementcode)->code == 'RCVC')
                                            <?php $hasRCVC = true; ?>
                                            @foreach($items as $tempItem)
                                                @if($tempItem->bl_no == $item->bl_no && optional($tempItem->movementcode)->code == 'DCHF')
                                                <?php $temp = ((strtotime($item->movement_date) - strtotime($tempItem->movement_date)) / (60 * 60 * 24) - $item->free_time + 1);
                                                    $dchfDate = $tempItem->movement_date;
                                                    if($temp < 0){
                                                        $temp = 0;
                                                    }
                                                ?>
                                                @endif
                                            @endforeach
                                            <td>{{$temp}} Day
                                                <a href="{{route('detention.showTriffSelectWithBlno',[
                                                    'id'=>$item->id,
                                                    'detention'=>$temp,
                                                    'dchfDate'=>$dchfDate,
                                                    'rcvcDate'=>$item->movement_date
                                                    ])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                    <i class="far fa-eye text-primary"></i>
                                                </a>
                                            </td>
                                            @elseif( ($item->bl_no !=null || $item->booking_no !=null)&& optional($item->movementcode)->code == 'DCHF')
                                                @if($hasRCVC)
                                                <?php $hasRCVC = false; ?>
                                                <td></td>
                                                @else
                                                <?php
                                                    $temp = (strtotime(date('Y-m-d')) - strtotime($item->movement_date)) / (60 * 60 * 24) - $item->free_time + 1;
                                                    if($temp < 0){
                                                        $temp = 0;
                                                    }
                                                    ?>
                                                    <td>  {{$temp}} Day
                                                        <a href="{{route('detention.showTriffSelectWithBlno',[
                                                            'id'=>$item->id,
                                                            'dchfDate'=>$item->movement_date,
                                                            'detention'=>$temp

                                                            ])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            @else
                                            <td></td>
                                            @endif --}}
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Edit')
                                                    <li>
                                                            <a href="{{route('movements.edit',['movement'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                <i class="far fa-edit text-success"></i>
                                                            </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movements.destroy',['movement'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if($movementId == false)
                            <div class="paginating-container">
                                {{ $items->appends(request()->query())->links()}}
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
<style>
        .export-form {
            display: inline; /* This ensures the form is displayed inline */
        }

        .export-form .btn-link {
            background: none; /* Remove the background color */
            border: none; /* Remove the border */
            color: #007bff; /* Set the link color */
            text-decoration: underline; /* Add underline to mimic link text */
            cursor: pointer; /* Show pointer cursor on hover */
        }
    </style>
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Movement?`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });

</script>
@endpush
