@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Customers</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                            <br>
                        <div class="row">
                            <div class="col-md-12 text-right mb-12">
                                @permission('Customers-Create')
                                    <a href="{{route('customers.create')}}" class="btn btn-primary">Add New Customer</a>
                                @endpermission
                                    <a class="btn btn-warning" href="{{ route('export.customers') }}">Export</a>
                            </div>
                        </div>
                    </div>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-12">
                            <label for="name">Customer</label>
                            <select class="selectpicker form-control" id="name" data-live-search="true" name="name[]" data-size="10"
                                title="{{trans('forms.select')}}"  multiple="multiple">
                                @foreach ($customer as $item)
                                    <option value="{{$item->name}}" {{$item->name == old('name',request()->input('name')) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="countryInput">{{trans('company.country')}}</label>
                        <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                            title="{{trans('forms.select')}}">
                            @foreach ($countries as $item)
                                <option value="{{$item->id}}" {{$item->id == old('country_id',request()->input('country_id')) ? 'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tax_card_noInput">Tax Card</label>
                            <input type="text" class="form-control" id="tax_card_noInput" name="tax_card_no" value="{{old('tax_card_no',request()->input('tax_card_no'))}}"
                                placeholder="Tax Card" autocomplete="off">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 text-center">
                        <button  type="submit" class="btn btn-success mt-3">Search</button>
                        <a href="{{route('customers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                    </div>
                </div>
            </form>

                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <!-- <th>Code</th> -->
                                        <th>Contact Person</th>
                                        <th>phone</th>
                                        <th>landline</th>
                                        <th>Country</th>
                                        <!-- <th>city</th> -->
                                        <th>address</th>
                                        <th>sales person</th>
                                        <th>Role</th>
                                        <th style="width: 100px;">EGP</th>
                                        <th style="width: 100px;">USD</th>
                                        <th>Status</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    @php
                                        $customer = $item->invoices->count();
                                    @endphp
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <!-- <td>{{$item->code}}</td> -->
                                            <td>{{$item->contact_person}}</td>
                                            <td>{{$item->phone}}</td>
                                            <td>{{$item->landline}}</td>
                                            <!-- <td>{{$item->currency}}</td> -->
                                            <td>{{optional($item->country)->name}}</td>
                                            <!-- <td>{{$item->city}}</td> -->
                                            <td>{{$item->address}} / {{$item->cust_address}}</td>
                                            <td>{{optional($item->User)->name}}</td>
                                            <td>
                                                <ul>
                                                    @foreach($item->CustomerRoles as $customerRole)
                                                    <li>{{optional($customerRole->role)->name}} </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>Credit: {{$item->credit_egp}} <br>
                                                Debit:  &nbsp;{{$item->debit_egp}}
                                            </td>
                                            <td>Credit: {{$item->credit}} <br>
                                                Debit:  &nbsp;{{$item->debit}}
                                            </td>
                                            <td class="text-center">
                                                @if($item->customer_kind == 0)
                                                    <span class="badge badge-info"> Primary </span>
                                                @else
                                                    <span class="badge badge-danger"> Validated </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @if($item->certificat == !null)
                                                    <li>
                                                        <a href='{{asset($item->certificat)}}' target="_blank">
                                                            <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @permission('Customers-Edit')
                                                    <li>
                                                        <a href="{{route('customers.edit',['customer'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Company-Show')
                                                    <li>
                                                        <a href="{{route('customers.show',['customer'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @if ($customer > 0)
                                                    @else
                                                    @permission('Customers-Delete')
                                                    <li>
                                                        <form action="{{route('customers.destroy',['customer'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
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
                            {{ $items->appends(request()->query())->links()}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Item?`,
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
