@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Tarrif No</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        </br>
                        <form id="createForm" action="{{route('detention.calculation')}}" method="POST">
                            @csrf
                       

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="countryInput">Select Triff</label>
                                    <select class="selectpicker form-control" id="Triff_id" data-live-search="true" name="Triff_id" data-size="10"
                                     title="{{trans('forms.select')}}" required>
                                        @foreach ($items as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('Triff_id',request()->input('Triff_id')) ? 'selected':''}}>{{$item->is_storge}} {{{optional($item->bound)->name}}} {{{optional($item->ports)->code}}} {{{optional($item->containersType)->name}}} </option>
                                        @endforeach
                                    </select>
                                    @error('Triff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror   
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="BLNo">BL No</label>
                                    <select class="selectpicker form-control" id="BLNoInput" data-live-search="true" name="bl_no" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                        @foreach ($movementsBlNo as $item)
                                            @if($item != null)
                                            <option value="{{$item}}" {{$item == old('bl_no',request()->input('bl_no')) ? 'selected':''}}>{{$item}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                                <div class="row">
                                
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success mt-3">Show Price</button>
                                </div>
                           </div>
                        </form>
                    </div>
                    @if(empty($movements))
                    @else
                    <h5><span style='color:#1b55e2';>BL Number:</span> {{$movements[0]->bl_no}}</h5>
                    @endif
                    <div class="widget-content widget-content-area">
                            
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Container Number</th>
                                            <th class="text-center">from</th>
                                            <th class="text-center">to</th>
                                            <th class="text-center">Free Time</th>
                                            <th class="text-center">Actual days</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php

                                        use App\Models\Containers\Period;
                                       $remainingDays = 0;
                                       $hasRCVC = false; 
                                       $rcvcMovement = $movements;
                                       $freetime = 0;
                                       $thereafter = false;
                                       $totalPrice = 0;
                                       ?>
                                       
                                    @forelse($movements as $movement)
                                    <?php $total = 0; ?>
                                    @if($movement->movementcode->code == 'DCHF')
                                        @foreach($movements as $item)
                                            @if($item->container->code == $movement->container->code && $item->movementcode->code == 'RCVC')
                                                <?php 
                                                    $hasRCVC = true; 
                                                    $rcvcMovement = $item;
                                                ?>
                                            @endif
                                        @endforeach
                                        
                                        <!-- check if movement has DCHF and RCVC or just have DCHF -->
                                        
                                        @if($hasRCVC == false)
                                            <?php $remainingDays = (strtotime(date('Y-m-d')) - strtotime($movement->movement_date)) / 86400 + 1; ?>
                                            <tr>
                                                <td class="text-center">{{$movement->container->code}}</td>
                                                <td class="text-center">{{$movement->movement_date}}</td>
                                                <td class="text-center">{{date('Y-m-d')}}</td>
                                                
                                                @if($movement->free_time != null)
                                                
                                                <?php $freetime = $movement->free_time; ?>
                                                <td class="text-center">{{$movement->free_time}}</td>
                                                @elseif(sizeof($periods) == 0)
                                                <?php $freetime = 0; ?>
                                                <td class="text-center">select triff first</td>
                                                @else
                                                <?php $freetime = $periods[0]->number_off_dayes; ?>
                                                <td class="text-center">{{$periods[0]->number_off_dayes}}</td>
                                                @endif
                                                <?php  $actualDays = (strtotime(date('Y-m-d')) - strtotime($movement->movement_date)) / 86400 - $freetime + 1; ?>
                                                @if($actualDays < 0 )
                                                    <?php  $actualDays = 0; ?>
                                                @endif
                                                <td class="text-center">{{$actualDays}}</td>
                                                <?php 
                                                $remainingFreeTime = $freetime;
                                                    if(sizeof($periods) > 0){
                                                            if($freetime > $periodtimeTotal){
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
                                                @if($total == 0)
                                                <td class="text-center">0</td>
                                                @else
                                                <td class="text-center">{{$total}} {{$demurrage[0]->currency}}</td>
                                                @endif
                                            </tr>
                                        @else
                                        <?php $remainingDays = (strtotime($rcvcMovement->movement_date) - strtotime($movement->movement_date)) / 86400 + 1 ?>
                                            <tr>
                                                <td class="text-center">{{$rcvcMovement->container->code}}</td>
                                                <td class="text-center">{{$movement->movement_date}}</td>
                                                <td class="text-center">{{$rcvcMovement->movement_date}}</td>
                                                @if($rcvcMovement->free_time != null)
                                                <?php $freetime = $rcvcMovement->free_time; ?>
                                                <td class="text-center">{{$rcvcMovement->free_time}}</td>
                                                @elseif(sizeof($periods) == 0)
                                                <?php $freetime = 0; ?>
                                                <td class="text-center">select triff first</td>
                                                @else
                                                <?php $freetime = $periods[0]->number_off_dayes; ?>
                                                <td class="text-center">{{$periods[0]->number_off_dayes}}</td>
                                                @endif
                                                <?php  $actualDays = (strtotime($rcvcMovement->movement_date) - strtotime($movement->movement_date)) / 86400 - $freetime + 1; ?>
                                                @if($actualDays < 0 )
                                                    <?php  $actualDays = 0; ?>
                                                @endif
                                                <td class="text-center">{{$actualDays}}</td>
                                                <?php 
                                                $remainingFreeTime = $freetime;
                                                if(sizeof($periods) > 0){
                                                    if($freetime > $periodtimeTotal){
                                                        $thereafter = true;
                                                    }
                                                    if($thereafter){
                                                        foreach($periods as $period){
                                                            if($period->period == 'Thereafter'){
                                                                $rate = $period->rate;
                                                            }
                                                        }
                                                        $remainingDays = $remainingDays - $freetime;
                                                        ($remainingDays < 0) ? $remainingDays = 0 : "";
                                                        $total = $remainingDays * $rate;
                                                        $thereafter = false;
                                                    }else{
                                                        
                                                        foreach($periods as $period){
                                                            // if($rcvcMovement->container->code == "TGHU2902378"){
                                                            //     dump("before");
                                                            //     dump(["period name"=>$period->period,"period days"=>$period->number_off_dayes,"remaining freetime"=>$remainingFreeTime,"remainingDays"=>$remainingDays,"Total"=>$total]);
                                                            // }
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
                                                                        if($remainingFreeTime < $period->number_off_dayes){
                                                                            $remainingDays -= $remainingFreeTime;
                                                                            $price = $period->rate * ($period->number_off_dayes- $remainingFreeTime);
                                                                            $total += $price;
                                                                            $remainingDays -= ($period->number_off_dayes- $remainingFreeTime);
                                                                            $remainingFreeTime = 0;
                                                                        }else{
                                                                        $remainingDays -= $period->number_off_dayes;
                                                                        $remainingFreeTime -= $period->number_off_dayes;
                                                                        }
                                                                        if($remainingFreeTime < 0){ $remainingFreeTime = 0;}
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
                                                            // if($rcvcMovement->container->code == "TGHU2902378"){
                                                            //     dump("after");
                                                            //     dump(["period name"=>$period->period,"period days"=>$period->number_off_dayes,"remaining freetime"=>$remainingFreeTime,"remainingDays"=>$remainingDays,"Total"=>$total]);
                                                            // }
                                                        }
                                                    }
                                                }else{
                                                    $total = 0;
                                                }
                                                    $totalPrice += $total;
                                                ?>
                                                @if($total == 0)
                                                <td class="text-center">0</td>
                                                @else
                                                <td class="text-center">{{$total}} {{$demurrage[0]->currency}}</td>
                                                @endif
                                            </tr>
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
                                        @if($totalPrice != 0)
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">{{$totalPrice}} {{$demurrage[0]->currency}}</td>
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

