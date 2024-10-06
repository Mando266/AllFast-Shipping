@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a a href="">{{ trans('menu.storage') }}</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ trans('menu.dentention_cal') }}</a>

                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Display the Cart -->

                    <div class="widget-content widget-content-area">   
                        <div class="alert alert-arrow-left alert-icon-left alert-light-warning"  id="warning_alert_msg"  role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" data-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <span> </span>
                        </div>
                        <form id="invoiceForm" action="{{route('dententions.store')}}" method="POST">
                            @csrf

                            @php
                                $calculation = session('calculation');
                                $input = session('input');
                            @endphp

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="BLNo">Booking No</label>
                                    <select class="selectpicker form-control" id="booking_no" data-live-search="true"
                                            name="booking_no" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($bookings as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('booking_no',isset($input) ? $input['booking_no'] : '') ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="Date">Container No</label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true"
                                            name="container_ids[]" data-size="10"
                                            title="{{trans('forms.select')}}" required multiple>
                                        <option
                                                value="all" {{ "all" == old('container_ids',isset($input) ? $input['container_ids'] : '') ? 'selected':'hidden'}}>
                                            All
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>From</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="from"
                                            data-size="10" id="from_code"
                                            title="{{trans('forms.select')}}" >
                                        @foreach ($movementsCode as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('from',isset($input) ? $input['from'] : '') || $item->code == 'DCHF'  ? 'selected' : ''}}>{{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('from'))
                                        <span class="text-danger">{{ $errors->first('from') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label>To</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="to"
                                            data-size="10" id="to_code"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($movementsCode as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('to',isset($input) ? $input['to'] : '') }}>{{$item->code}} </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('to'))
                                        <span class="text-danger">{{ $errors->first('to') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Till Date</label>
                                    <input type="date" name="to_date" class="form-control"
                                           value="{{old('to_date',isset($input) ? $input['to_date'] : '')}}">
                                    @if ($errors->has('to_date'))
                                        <span class="text-danger">{{ $errors->first('to_date') }}</span>
                                    @endif
                                </div>
                                 <div class="form-group col-md-2 mt-5 ml-2">
                                        <input type="checkbox"  id="apply_first_day" name="apply_first_day"
                                               value="1" {{ isset($input['apply_first_day']) ?  'checked': '' }} checked>
                                        <label for="apply_first_day">{{ trans('home.add_first') }}</label>
                                </div>
                                 <div class="form-group col-md-2 mt-5 ml-2">
                                        <input type="checkbox"  id="apply_last_day" name="apply_last_day"
                                               value="1"  {{ isset($input['apply_last_day']) ?  'checked': '' }} >
                                        <label for="apply_last_day">{{ trans('home.add_last') }}</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info mt-3">Calculate</button>
                                </div>
                            </div>


                            @isset($calculation)
                            
                            <h4 style="color:#1b55e2">Calculation
                                    <h4>
                                        <table id="charges" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="col-md-2 text-center">Container No</th>
                                                <th class="col-md-1 text-center">Cycle Status</th>
                                                <th class="col-md-1 text-center">From Mov.Code</th>
                                                <th class="col-md-2 text-center">From Mov.Date</th>
                                                <th class="col-md-1 text-center">to Mov.Code</th>
                                                <th class="col-md-2 text-center">Till Date</th>
                                                <th class="col-md-6 text-center" colspan="3">Calculation Details</th>
                                                <th class="col-md-2 text-center">Total ({{$calculation['currency']}})
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                             @if(count($calculation['containers']) > 0)
                                                <input type="hidden" id="calculation" value="{{$calculation['containers']}}">
                                                <input type="hidden" id="periods" value="{{$calculation['containers'][0]['periods']}}">
                                            @endif
                                            @foreach($calculation['containers'] as $item)
                                                <tr>
                                                    <td class="col-md-2 text-center">{{$item['container_no']}} {{$item['container_type']}}</td>
                                                    <td class="col-md-2 text-center">{{$item['status']}}</td>
                                                    <td class="col-md-1 text-center">{{$item['from_code']}}</td>
                                                    <td class="col-md-2 text-center">{{$item['from']}}</td>
                                                    <td class="col-md-1 text-center">{{$item['to_code']}}</td>
                                                    <td class="col-md-2 text-center">{{$item['to']}}</td>
                                                    <td class="col-md-3" style="border-right-style: hidden;">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['name'] }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2" style="border-right-style: hidden;">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['days'] }} Days <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2" style="border-right-style: hidden;">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['total'] }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2 text-center">
                                                        {{$item['total']}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2"></td>
                                                <td class="col-md-2 text-center" id="grandTotal">
                                                    {{$calculation['grandTotal']}}
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-warning mt-3" id="create_extention">{{ trans('home.extention') }}</button>
                                            <button type="submit" class="btn btn-warning mt-3" id="create_invoive">{{ trans('home.c_invoice') }}</button>
                                        </div>


                            @endisset
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @push('styles')
            <style>
                .button-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center; /* Center buttons horizontally */
                    gap: 10px; /* Adjust the space between the buttons */
                }

                .btn-custom {
                    background-color: #ffc107; /* Set the background color to yellow */
                    color: #000; /* Set the text color to black */
                    border: none; /* Remove button border */
                    padding: 10px 20px; /* Adjust padding as needed */
                    border-radius: 5px; /* Add rounded corners */
                    text-decoration: none; /* Remove underlines from links */
                    transition: background-color 0.3s; /* Add a smooth hover effect */
                }

                .btn-custom:hover {
                    background-color: #ff9800; /* Change the background color on hover */
                }

                /* Style the cart container */
                #invoice-cart {
                    border: 1px solid #ddd;
                    padding: 20px;
                    margin-top: 20px;
                    background-color: #f9f9f9;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                /* Style the cart header */
                .cart-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /* Style the cart items list */
                #cart-items {
                    list-style-type: none;
                    padding: 0;
                }

                /* Style individual cart items */
                .cart-item {
                    border: 1px solid #ccc;
                    padding: 10px;
                    margin-bottom: 10px;
                    background-color: #fff;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /* Style the Create Invoice button */
                #create-invoice {
                    margin-top: 20px;
                }

                /* Style the Add to Invoice button */
                #add-to-cart {
                    margin-bottom: 20px;
                }

                /* Style the Clear Cart button */
                #clear-cart {
                    margin-top: -10px;
                }

                /* Style the remove button */
                .remove-button {
                    background-color: #ff6961;
                    color: #fff;
                    border: none;
                    border-radius: 50%;
                    cursor: pointer;
                    font-size: 16px;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-left: 10px;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

            <script>
                let company_id = "{{auth()->user()->company_id}}";
                let selectedCodes = '{{ implode(',',$input['container_ids'] ??[]) }}'
                selectedCodes = selectedCodes.split(',').filter(item => item !== '')
                $(function() {
                    if($('#booking_no').val()){
                        $('#booking_no').change();
                        $('#from_code').val({{ old('from',isset($input) ? $input['from'] : '') }}).trigger('change');
                        $('#to_code').val({{ old('to',isset($input) ? $input['to'] : '') }}).trigger('change');
                    }
                });

                $('#booking_no').change(function (e) {
                     let container = $('#port'); 
                     let booking_no = $('#booking_no').val();  
                    container.empty();
                    container.append(`<option value='all'  ${selectedCodes.includes( 'all') ? 'selected' : ''}>All</option>`);
                    $.ajax({
                        url: '{{route("api.blContainers")}}',
                        type: 'GET',
                        data: {
                            booking_no: booking_no,
                            company_id: company_id,
                        }
                    }).done(function (data) {
                        if(data.missingContainers.length){
                            $('#warning_alert_msg span').text(`containers with this code ( ${ data.missingContainers.join(",")} )has no movement`);
                            $('#warning_alert_msg').show();
                        }
                        $.each(data.containersMov, function (index, value) {
                             let selected = selectedCodes.includes(""+value.id) ? 'selected' : '';
                            container.append( `<option value='${value.id}' ${selected}> ${value.code} </option>`);
                        });
                        container.selectpicker('refresh');
                    })
                });
                $('#port').change(function () {
                    var selectedValue = $(this).val();
                    if (selectedValue.length > 1 && selectedValue.includes('all')) {
                        selectedValue = selectedValue.filter(function (value) {
                            return value !== 'all';
                        });
                        $(this).val(selectedValue);
                    }
                
                    if (selectedValue.includes('all')) {
                        $('#port option:not(:selected)').prop('disabled', true);
                    } else {
                        $('#port option').prop('disabled', false);
                    }
                    $('#port').selectpicker('refresh');
                });
            </script>
            <script>
                $('#create_invoive').click(function (e) {
                    e.preventDefault();
                    let formData = $('#invoiceForm').serialize();
                    let grandTotalText = $('#grandTotal').text();
                    let grandTotal = grandTotalText.match(/\d+/);
                        grandTotal = grandTotal ? parseInt(grandTotal[0], 10) : 0;
                    let periods = $('#periods').val();
                    let calculation = $('#calculation').val();
                        formData += '&booking_ref=' + encodeURIComponent($('#booking_no').val());
                        formData += '&grandTotal=' + encodeURIComponent(grandTotal);
                        formData += '&periods=' + encodeURIComponent(periods);
                        formData += '&calculation=' + encodeURIComponent(calculation);
                        window.location.href = "{{ route('debit-invoice') }}?" + formData;
                });

                $('#create_extention').click(function (e) {
                    e.preventDefault();
                    let formData = $('#invoiceForm').serialize();
                    let calculation = $('#calculation').val();
                    formData += '&data=' + encodeURIComponent(calculation);
                    formData += '&booking_ref=' + encodeURIComponent($('#booking_no').val());
                    window.location.href = "{{ route('extention-dententions') }}?" + formData;
                });
            </script>

        @endpush
