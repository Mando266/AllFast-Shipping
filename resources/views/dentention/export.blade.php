@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a a href="">{{ trans('menu.calculation_period') }}</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ trans('menu.storage_cal') }}</a>
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
                        <form  action="{{route('export_calculation_period')}}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="{{old('from_date')}}" required>

                                </div>
                                <div class="form-group col-md-4">
                                    <label>Till Date</label>
                                    <input  type="date" name="to_date" class="form-control" value="{{old('to_date')}}" required>
                                </div>
                                <div class="col-md-4 mt-4 text-right">
                                    <button type="submit" class="btn btn-warning mt-3" >Export</button>
                                </div>

                            </div>
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
