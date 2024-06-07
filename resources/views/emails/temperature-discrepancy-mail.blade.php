<style>
    table {
        margin: 0 auto; /* Center the table */
        border-collapse: collapse;
    }

    table thead th {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table tbody td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .text-center {
        text-align: center;
    }
</style>

<p>Dear Customer,</p>
<p>
    Please note that we have been notified by the terminal with a temperature discrepancies by the time of the gate in for the below containers:
</p>
<table>
    <thead>
    <tr>
        <th>Container No</th>
        <th>TEMPERATURE</th>
        <th>GATE IN DATE</th>
        <th>TEMP BY THE TIME OF GATE IN</th>
        <th>MAIN LANE NO</th>
        <th>SECONDARY LANE NO</th>
        <th>COMMODITY DESCRIPTION</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($details as $detail)
        <tr>
            <td>{{ $detail['container_no'] }}</td>
            <td>{{ $detail['temperature'] }}</td>
            <td>{{ $detail['gateInDate'] }}</td>
            <td>{{ $detail['tempDiff'] }}</td>
            <td>{{ $detail['mainLaneNo'] }}</td>
            <td>{{ $detail['secondaryLaneNo'] }}</td>
            <td>{{ $detail['commodityDescription'] }}</td>
        </tr>
    @empty
        <tr class="text-center">
            <td colspan="7">{{ trans('home.no_data_found') }}</td>
        </tr>
    @endforelse
    </tbody>
</table>