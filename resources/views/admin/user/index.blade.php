@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-one">
                        <div class="widget-heading">
                            <nav class="breadcrumb-two" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Administration</a></li>
                                    <li class="breadcrumb-item active"><a href="{{route('users.index')}}">Users</a></li>
                                    <li class="breadcrumb-item"></li>
                                </ol>
                            </nav>
                            @permission('User-Create')
                            <div class="row">
                                <div class="col-md-12 text-right mb-5">
                                <a href="{{route('users.create')}}" class="btn btn-primary">{{trans('user.create_new')}}</a>
                                </div>
                            </div>
                            @endpermission
                        </div>
                        <form action="{{route('users.index')}}" method="GET">
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-condensed mb-4">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th><input type="text" class="form-control" name="name" placeholder="{{trans('forms.search')}}" autocomplete="off" value="{{request()->input('name')}}"></th>
                                                <th><input type="text" class="form-control" name="full_name" placeholder="{{trans('forms.search')}}" autocomplete="off" value="{{request()->input('full_name')}}"></th>
                                                {{-- <th>{{trans('user.employee_no')}}</th> --}}
                                                <th></th>
                                                <th>
                                                    <select class="selectpicker show-tick form-control" multiple data-live-search="true" data-selected-text-format="count > 1"
                                                        name="role[]" title="{{trans('forms.select')}}" data-width="100%">
                                                        @foreach ($roles as $item)
                                                        <option value="{{$item->id}}" {{in_array($item->id,request()->input('role',[])) ? 'selected' :''}}>{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select class="selectpicker form-control"  name="status" title="{{trans('forms.select')}}">
                                                        <option value="enabled" {{ request()->input('status') == "enabled" ? 'selected':'' }}>Enabled</option>
                                                        <option value="disabled" {{ request()->input('status') == "disabled" ? 'selected':'' }}>Disabled</option>
                                                    </select>
                                                </th>
                                                <th>
                                                    <button type="submit" class="btn btn-success btn-icon btn-sm"><i class="fa fa-search"></i></button>
                                                    <a href="{{ route('users.index') }}" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-ban"></i></a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th>Agent</th>
                                                <th>{{trans('user.user_name')}}</th>
                                                <th>{{trans('user.full_name')}}</th>
                                                {{-- <th>{{trans('user.employee_no')}}</th>--}}
                                                <th>{{trans('user.email')}}</th> 
                                                <th class="text-center">{{trans('user.role')}}</th>
                                                <th class="text-center">{{trans('user.status')}}</th>
                                                <th class='text-center' style='width:140px;'></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $item)
                                                <tr>
                                                    <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                                    <td>{{optional($item->agent)->name}}</td>
                                                    <td>{{$item->name}}</td>
                                                    <td>{{$item->full_name}}</td>
                                                    <td>{{$item->email}}</td>
                                                    <td class="text-center"> {{ optional($item->roles->first())->name }}</td>
                                                    <td class="text-center">
                                                        @if($item->is_active )
                                                            <span class="badge badge-info"> Enabled </span>
                                                        @else
                                                            <span class="badge badge-danger"> Disabled </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            @permission('User-Edit')
                                                            <li>
                                                                <a href="{{route('users.edit',['user'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                    <i class="far fa-edit text-success"></i>
                                                                </a>
                                                            </li>
                                                            @endpermission
                                                            @permission('User-Show')
                                                            <li>
                                                                <a href="{{route('users.show',['user'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                            </li>
                                                            @endpermission 
                                                            <!-- @permission('User-Delete')
                                                            <li>
                                                                <form action="{{route('users.destroy',['user'=>$item->id])}}" method="post">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                                </form> 
                                                            </li>
                                                            @endpermission -->
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
                                    {{$items->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
        </div>
    </div>
@endsection
