@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
</br>
                        @permission('Demurrage-Create')
                        <form id="createForm" action="{{route('detention.showDetention')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 text-left">
                                    <h6>Container No: {{$container_no}}</h6>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="countryInput">Select Triff</label>
                                    <select class="selectpicker form-control" id="Triff_id" data-live-search="true" name="Triff_id" data-size="10"
                                     title="{{trans('forms.select')}}">
                                        @foreach ($items as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('Triff_id',request()->input('Triff_id')) ? 'selected':''}}>{{$item->is_storge}} {{{optional($item->bound)->name}}} {{{optional($item->ports)->code}}} {{{optional($item->containersType)->name}}}</option>
                                        @endforeach
                                    </select>
                                    @error('Triff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror   
                                </div>
                            </div>

                                <input type="hidden" name="movement_id" value="{{$movement->id}}">
                                <input type="hidden" name="detention" value="{{$detention}}">
                                <input type="hidden" name="dchfDate" value="{{$dchfDate}}">
                                <input type="hidden" name="rcvcDate" value="{{$rcvcDate}}">
                                <div class="row">
                                
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success mt-3">Show Price</button>
                                </div>
                           </div>
                        </form>
                        @endpermission
                    </div>
                    <div class="widget-content widget-content-area">
                            <div class="table-responsive">
                                        <?php 
                                            $remaining_days = $detention;
                                            $total = 0;
                                        ?>
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th class="text-center">container Type</th>
                                            <th class="text-center">free time</th>
                                            <th class="text-center">DCHF</th>
                                            @if($rcvcDate != null)
                                            <th class="text-center">RCVC</th>
                                            @else
                                            <th class="text-center">Today</th>
                                            @endif
                                            <!-- <th></th> -->
                                            <th class="text-center">FREE TIME TILL (-1)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$containerType}}</td>

                                            @if($movement->free_time != null)
                                            <?php $freetime = $movement->free_time; ?>
                                            <td class="text-center">{{$movement->free_time}}</td>
                                            @elseif($periods == null)
                                            <td class="text-center">Select Triff first</td>
                                            <?php $freetime = 0; ?>
                                            @else
                                            <?php $freetime = $periods[0]->number_off_dayes; ?>
                                            <td class="text-center">{{$periods[0]->number_off_dayes}}</td>
                                            @endif

                                            <td class="text-center">{{$dchfDate}}</td>
                                            @if($rcvcDate != null)
                                            <td class="text-center">{{$rcvcDate}}</td>
                                            @else
                                            <td class="text-center">{{date('Y-m-d')}}</td>
                                            @endif
                                            <!-- <td></td> -->
                                            @if($freetime != 0)
                                            <td class="text-center">{{date('Y-m-d', strtotime($dchfDate. ' + '.$freetime .' days'. ' - 1 days'))}}</td>
                                            @else
                                            <td class="text-center">Select Triff first</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">total duration</td>
                                            @if($rcvcDate != null)
                                            <td class="text-center">{{(strtotime($rcvcDate) - strtotime($dchfDate))/ (60 * 60 * 24) + 1}}</td>
                                            <?php $remaining_days = (strtotime($rcvcDate) - strtotime($dchfDate))/ (60 * 60 * 24) + 1; ?>
                                            @else
                                            <td class="text-center">{{(strtotime(date('Y-m-d')) - strtotime($dchfDate))/ (60 * 60 * 24) + 1}}</td>
                                            <?php $remaining_days = (strtotime(date('Y-m-d')) - strtotime($dchfDate))/ (60 * 60 * 24) + 1; ?>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">Free Time</td>
                                            @if($movement->free_time != null)
                                            <?php $freetime = $movement->free_time; ?>
                                            <td class="text-center">{{$movement->free_time}}</td>
                                            @elseif($periods == null)
                                            <td class="text-center">Select Triff first</td>
                                            <?php $freetime = 0; ?>
                                            @else
                                            <?php $freetime = $periods[0]->number_off_dayes; ?>
                                            <td class="text-center">{{$periods[0]->number_off_dayes}}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">detention days</td>
                                            @if($freetime != 0 && strtotime($rcvcDate) != null)
                                            <?php  $actualDays = ((strtotime($rcvcDate) - strtotime($dchfDate))/ (60 * 60 * 24) + 1) - $freetime; ?>
                                                @if($actualDays < 0 )
                                                    <?php  $actualDays = 0; ?>
                                                @endif
                                            <td class="text-center">{{$actualDays}}</td>
                                            @elseif($freetime != 0 && strtotime($rcvcDate) == null)
                                            <?php  $actualDays = ((strtotime(date('Y-m-d')) - strtotime($dchfDate))/ (60 * 60 * 24) + 1) - $freetime; ?>
                                                @if($actualDays < 0 )
                                                    <?php  $actualDays = 0; ?>
                                                @endif
                                            <td class="text-center">{{$actualDays}}</td>
                                            @else
                                            <td class="text-center">Select Triff first</td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Desrcp</th>
                                            <th class="text-center">no of days</th>
                                            <th class="text-center">from</th>
                                            <th class="text-center">to</th>
                                            <th class="text-center">Actual days</th>
                                            <th class="text-center">rate</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $remainingFreeTime = $freetime; ?>
                                        
                                        @forelse ($periods as $item)
                                        <tr>
                                            <td class="text-center">{{$item->period}}</td>
                                            @if($item->period == "Thereafter")
                                            <td class="text-center">unlimited</td>
                                            @else
                                            <td class="text-center">{{$item->number_off_dayes}}</td>
                                            @endif
                                            @php 
                                                $fromDate = date('Y-m-d', strtotime($dchfDate. ' + '.$item->number_off_dayes .' days'));
                                            @endphp
                                            @if($freetime < $periodtimeTotal)
                                                @if($item->period == "free time")
                                                <td class="text-center">{{$dchfDate}}</td>
                                                <td class="text-center">{{date('Y-m-d', strtotime($dchfDate. ' + '.$item->number_off_dayes .' days'. ' - 1 days'))}}</td>
                                                <?php 
                                                if($remainingFreeTime > $item->number_off_dayes){
                                                    $remainingFreeTime -= $item->number_off_dayes;
                                                }
                                                 
                                                ?>
                                                
                                                    @if($remaining_days > $freetime)
                                                    <?php 
                                                    $remaining_days -= $item->number_off_dayes; 
                                                    $daysNum = $item->number_off_dayes;
                                                    ?>
                                                    <td class="text-center">{{$freetime}}</td>
                                                    @else
                                                    <?php 
                                                    $remaining_days -= $item->number_off_dayes; 
                                                    $daysNum = $remaining_days;
                                                    ?>
                                                    <td class="text-center">{{$remaining_days}}</td>
                                                    @endif
                                                <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                <td class="text-center">0 {{$demurrage[0]->currency}}</td>
                                                @elseif($item->period == "Thereafter")
                                                    @if($remaining_days != 0)
                                                        <td class="text-center">{{$fromDate}}</td>
                                                        <td class="text-center">{{date('Y-m-d', strtotime($fromDate. ' + '.$remaining_days .' days'. ' - 1 days'))}}</td>
                                                        <td class="text-center">{{$remaining_days}}</td>
                                                        <?php 
                                                        $daysNum = $remaining_days;
                                                        $remaining_days -= $remaining_days;
                                                        ?> 
                                                        <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                        <td class="text-center">{{$daysNum * $item->rate}} {{$demurrage[0]->currency}}</td>
                                                        <?php $total += $daysNum * $item->rate; ?>
                                                    @else
                                                        <td class="text-center">{{$fromDate}}</td>
                                                        <td class="text-center">unlimited</td>
                                                        <td class="text-center">0</td>
                                                        <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                        <td class="text-center">0 {{$demurrage[0]->currency}}</td>
                                                    @endif

                                                @elseif($remainingFreeTime == 0)
                                                
                                                    <td class="text-center">{{$fromDate}}</td>
                                                    <td class="text-center">{{date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'. ' - 1 days'))}}</td>
                                                    <?php 
                                                    $fromDate = date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'));
                                                    ?>
                                                    @if($remaining_days > $item->number_off_dayes)
                                                        <?php 
                                                        $remaining_days -= $item->number_off_dayes; 
                                                        $daysNum = $item->number_off_dayes;
                                                        ?>
                                                        <td class="text-center">{{$item->number_off_dayes}}</td>
                                                    @else
                                                        <td class="text-center">{{$remaining_days}}</td>
                                                        <?php 
                                                        if($remaining_days == 0)
                                                        {
                                                            $daysNum = 0;
                                                            $remaining_days = 0;
                                                        }else{
                                                            $daysNum = $remaining_days;
                                                            $remaining_days -= $remaining_days;
                                                        }
                                                        
                                                        ?>
                                                    @endif
                                                    <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <td class="text-center">{{$daysNum * $item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <?php $total += $daysNum * $item->rate; ?>
                                                @else
                                                    @if($remainingFreeTime >= $item->number_off_dayes)
                                                        <td class="text-center">{{$fromDate}}</td>
                                                        <td class="text-center">{{date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'. ' - 1 days'))}}</td>
                                                        <?php 
                                                        $fromDate = date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'));
                                                        ?>
                                                        <td class="text-center">0</td>
                                                        <?php 
                                                        $remaining_days -= $item->number_off_dayes; 
                                                        $remainingFreeTime -= $item->number_off_dayes;
                                                        $daysNum = 0;
                                                        if($remainingFreeTime < 0 ){
                                                            $remainingFreeTime = 0;
                                                        }    ?>
                                                    @else 
                                                        <td class="text-center">{{$fromDate}}</td>
                                                        <td class="text-center">{{date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'. ' - 1 days'))}}</td>
                                                        <?php 
                                                        $fromDate = date('Y-m-d', strtotime($fromDate. ' + '.$item->number_off_dayes .' days'));
                                                        ?>
                                                        @if($remaining_days > $item->number_off_dayes)
                                                            <?php 
                                                            $remaining_days -= $item->number_off_dayes; 
                                                            $daysNum = $item->number_off_dayes - $remainingFreeTime;
                                                            ?>
                                                            <td class="text-center">{{$daysNum}}</td>
                                                        @else
                                                            <td class="text-center">{{$remaining_days}}</td>
                                                            <?php 
                                                            if($remaining_days == 0)
                                                            {
                                                                $daysNum = 0;
                                                                $remaining_days = 0;
                                                            }else{
                                                                $daysNum = $remaining_days - $remainingFreeTime;
                                                                if($daysNum < 0) {
                                                                    $daysNum=0;
                                                                }
                                                                
                                                                $remaining_days -= $remaining_days;
                                                                if($remaining_days <0){
                                                                    $remaining_days=0;
                                                                }
                                                            }
                                                            
                                                            ?>
                                                        @endif
                                                        <?php $remainingFreeTime = 0;  ?>
                                                    @endif
                                                
                                                    <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <td class="text-center">{{$daysNum * $item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <?php $total += $daysNum * $item->rate; ?>
                                                @endif
                                            @else
                                                @if($item->period == "free time")
                                                <td class="text-center">{{$dchfDate}}</td>
                                                <td class="text-center">{{date('Y-m-d', strtotime($dchfDate. ' + '.$freetime .' days'. ' - 1 days'))}}</td>
                                                <?php 
                                                $fromDate = date('Y-m-d', strtotime($dchfDate. ' + '.$freetime .' days'));
                                                ?>
                                                    @if($remaining_days > $freetime)
                                                    <?php 
                                                    $remaining_days -= $freetime; 
                                                    $daysNum = $freetime;
                                                    ?>
                                                    <td class="text-center">{{$freetime}}</td>
                                                    @else
                                                    <?php 
                                                    $remaining_days -= $remaining_days; 
                                                    $daysNum = $remaining_days;
                                                    ?>
                                                    <td class="text-center">{{$remaining_days}}</td>
                                                    @endif
                                                <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                <td class="text-center">0 {{$demurrage[0]->currency}}</td>
                                                @elseif($item->period == "Thereafter")
                                                    @if($remaining_days != 0)
                                                    <td class="text-center">{{$fromDate}}</td>
                                                    <td class="text-center">{{date('Y-m-d', strtotime($fromDate. ' + '.$remaining_days .' days'. ' - 1 days'))}}</td>
                                                    <td class="text-center">{{$remaining_days}}</td>
                                                    <?php 
                                                    $daysNum = $remaining_days;
                                                    $remaining_days -= $remaining_days;
                                                    ?> 
                                                    <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <td class="text-center">{{$daysNum * $item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <?php $total += $daysNum * $item->rate; ?>
                                                    @else
                                                    <td class="text-center">{{$fromDate}}</td>
                                                    <td class="text-center">unlimited</td>
                                                    <td class="text-center">0</td>
                                                    <td class="text-center">{{$item->rate}} {{$demurrage[0]->currency}}</td>
                                                    <td class="text-center">0 {{$demurrage[0]->currency}}</td>
                                                    @endif
                                                @endif
                                            @endif
                                            @empty
                                            <tr class="text-center">
                                                <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                            </tr>
                                            
                                        @endforelse
                                        
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        @if($total != 0)
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">{{$total}} {{$demurrage[0]->currency}}</td>
                                        @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

