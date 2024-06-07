@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Movement Activity Codes</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('ContainersMovement-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('container-movement.create')}}" class="btn btn-primary">Add New Movement Activity Code</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="containersMovementsInput">Movement Activity Codes</label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="code" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($movementscode as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('code',request()->input('code')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button  type="submit" class="btn btn-success mt-3">Search</button>
                        <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                    </div>
                </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Activity Name</th>
                                        <th>Activity code</th>
                                        <th>container status</th>
                                        <th>stock type</th>
                                        <th>NEXT Possible Activity</th>
                                        
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    @php
                                            $movement = $item->movements->count();
                                    @endphp

                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{{optional($item->containerstatus)->name}}}</td>
                                            <td>{{{optional($item->containerstock)->code}}}</td>
                                            <td>{{$item->next_move}}</td>
                                     
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('ContainersMovement-Edit')
                                                    <li>
                                                        <a href="{{route('container-movement.edit',['container_movement'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                @if ($movement > 0)
                                                    @else 
                                                    @permission('ContainersMovement-Delete')
                                                    <li>
                                                        <form action="{{route('container-movement.destroy',['container_movement'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form> 
                                                    </li>
                                                    @endpermission
                                                @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection