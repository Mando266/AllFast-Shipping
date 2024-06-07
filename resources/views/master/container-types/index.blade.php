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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Container Types</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('ContainersTypes-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('container-types.create')}}" class="btn btn-primary">Add New Container Type</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>description</th>
                                        <th>code</th>
                                        <th>Category</th>
                                        <th>Iso NO</th>
                                        <th>width</th>
                                        <th>heights</th>
                                        <th>lenght</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    @php
                                            $container = $item->containers->count();
                                    @endphp
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{$item->category}}</td>
                                            <td>{{$item->iso_no}}</td>
                                            <td>{{$item->width}}</td>
                                            <td>{{$item->heights}}</td>
                                            <td>{{$item->lenght}}</td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('ContainersTypes-Edit')
                                                    <li>
                                                        <a href="{{route('container-types.edit',['container_type'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                @if ($container > 0)
                                                    @else
                                                    @permission('ContainersTypes-Delete')
                                                    <li>
                                                        <form action="{{route('container-types.destroy',['container_type'=>$item->id])}}" method="post">
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
                                            <td colspan="7">{{ trans('home.no_data_found')}}</td>
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
