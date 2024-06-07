@extends('layouts.app')

@section('content')

<div class="form-image">

    <div class="l-image">

        <img src="{{asset('assets/img/allfastLogo.jpeg')}}" alt="MSL Logo" style="width: 90%;">

    </div>

</div>



@endsection

@push('styles')

    <link href="{{asset('plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />

@endpush

@push('scripts')

    <script src="{{asset('plugins/apex/apexcharts.min.js')}}"></script>

    <script src="{{asset('assets/js/dashboard/dash_1.js')}}"></script>

@endpush

