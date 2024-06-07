<!-- preview.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Cart Preview</title>
    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
</head>
<body>
<h2>Invoice BreakDown</h2>

<!-- Display the cart data in a table -->
<table class="table table-bordered tableStyle">
    <thead>
    <tr>
        <th class="col-md-2 text-center">Container No</th>
        <th class="col-md-8 text-center" colspan="4">Calculation Details</th>
        <th class="col-md-2 text-center">Total</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($cartData))
        @foreach($cartData as $item)
            <tr>
                <td class="col-md-12 text-center" style="font-weight: bold; font-size: 18px;"
                    colspan="6">{{$item['triffValue']}}</td>
            </tr>
            @foreach($item['calculationData']['containers'] as $calculation)
                <tr>
                    <td class="col-md-2 text-center">{{$calculation['container_no']}} {{$calculation['container_type']}}</td>
                    <td class="col-md-2" style="border-right-style: hidden;">
                        From: {{$calculation['from']}} <br>
                        To: {{$calculation['to']}}
                    </td>
                    <td class="col-md-2" style="border-right-style: hidden;">
                        @foreach($calculation['periods'] as $period)
                            {{ $period['name'] }} <br>
                        @endforeach
                    </td>
                    <td class="col-md-2" style="border-right-style: hidden;">
                        @foreach($calculation['periods'] as $period)
                            {{ $period['days'] }} Days <br>
                        @endforeach
                    </td>
                    <td class="col-md-2">
                        @foreach($calculation['periods'] as $period)
                            {{ $period['total'] }} <br>
                        @endforeach
                    </td>
                    <td class="col-md-2 text-center">
                        {{$calculation['total']}}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2">Total:</td>
                <td class="col-md-2 text-center">
                    {{$item['calculationData']['grandTotal']}}
                </td>
            </tr>
        @endforeach
    @else
        <tr class="text-center">
            <td colspan="20">{{ trans('home.no_data_found')}} Please ADD CALCULATION IN CART</td>
        </tr>
    @endif
    </tbody>
    @isset($cartData)
        @if(count($cartData) > 1)
            <tfoot>
            <tr>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2" style="border-right-style: hidden;"></td>
                <td class="col-md-2">Total Invoice:</td>
                <td class="col-md-2 text-center">
                    {{$total}}
                </td>
            </tr>
            </tfoot>
        @endif
    @endisset
</table>

<!-- Add a Print button -->
<div class="row">
    <div class="col-md-12 text-center">
        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print</button>
    </div>
</div>
<script src="{{asset('bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
</body>
<style>
    @media print {
        .search_row,
        .hide {
            display: none !important;
        }
    }

    .tableStyle {
        font-size: 14px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
        margin-bottom: 1rem;
        height: 50px;
        color: black;
        text-transform: uppercase;
        padding: .75rem;
    }

    .thstyle {
        background-color: #80808061 !important;
        color: #000 !important;
        height: 50px;
        border: 1px solid #000 !important;
        font-size: 16px !important;
        font-weight: bolder !important;
    }
</style>
</html>
