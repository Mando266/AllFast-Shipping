<label>Invoice EGP
    <input value="{{ number_format($invoiceEgp, 2, '.', ',') }}"
           class="form-control border-0"
           disabled>
</label>
<label>Invoice USD
    <input value="{{ number_format($invoiceUsd, 2, '.', ',') }}"
           class="form-control border-0"
           disabled>
</label>
<label>Total USD
    <input value="{{ number_format($totalUsd, 2, '.', ',') }}" class="form-control border-0"
           disabled>
</label>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-condensed mb-4">
        <thead>
        <tr>
            <th>#</th>
            <th><a class="sort-results" data-name="invoice_no">Invoice Number<i class="fas fa-sort sort-icon"></i></a></th>
            <th><a class="sort-results" data-name="invoice_date">Date<i class="fas fa-sort sort-icon"></i></a></th>
            <th><a class="sort-results" data-name="shipping_line_id">Line<i class="fas fa-sort sort-icon"></i></a></th>
            <th><a class="sort-results" data-name="port_id">Port<i class="fas fa-sort sort-icon"></i></a></th>
            <th>Vessel</th>
            <th>Voyage</th>
            <th>Charges Applied</th>
            <th>Full Containers</th>
            <th>Empty Containers</th>
            <th>Bookings</th>
            <th><a class="sort-results" data-name="total_usd">Total USD<i class="fas fa-sort sort-icon"></i></a></th>
            <th><a class="sort-results" data-name="invoice_usd">Invoice USD<i class="fas fa-sort sort-icon"></i></a></th>
            <th><a class="sort-results" data-name="invoice_egp">Invoice EGP<i class="fas fa-sort sort-icon"></i></a></th>
            <th class='text-center' style='width:100px;'></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($invoices as $key => $invoice)
            <tr>
                <td>{{ $invoices->firstItem() +  $key }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ $invoice->line->name ?? '' }}</td>
                <td>{{ $invoice->port->name ?? '' }}</td>
                <td>{{ $invoice->vesselsNames() }}</td>
                <td>{{ $invoice->voyagesNames() }}</td>
                <td>{{ $invoice->selected_costs }}</td>
                <td>{{ $invoice->fullCount() }}</td>
                <td>{{ $invoice->emptyCount() }}</td>
                <td>{{ $invoice->rows->pluck('bl_no')->unique()->implode("\n") }}</td>
                <td>{{ $invoice->total_usd }}</td>
                <td>{{ $invoice->invoice_usd }}</td>
                <td>{{ $invoice->invoice_egp }}</td>
                <td class="text-center">
                    <ul class="table-controls">
                        <li>
                            <a href="{{route('port-charge-invoices.edit', $invoice->id)}}"
                               data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="edit">
                                <i class="far fa-edit text-success"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('port-charge-invoices.show', $invoice->id)}}"
                               data-toggle="tooltip" data-placement="top" title=""
                               data-original-title="show">
                                <i class="far fa-eye text-primary"></i>
                            </a>
                        </li>
                        <li>
                            <form action="{{route('port-charge-invoices.destroy', $invoice->id)}}"
                                  method="post">
                                @method('DELETE')
                                @csrf
                                <button style="border: none; background: none;"
                                        type="submit"
                                        class="fa fa-trash text-danger show_confirm"></button>
                            </form>
                        </li>
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
    {{ $invoices->render() }}
</div>
