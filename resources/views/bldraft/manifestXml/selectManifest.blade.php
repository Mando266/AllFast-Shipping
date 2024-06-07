@extends('layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Manifest</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">XML Gates</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create XML Manifest</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{ route('xml.create') }}" method="get">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="bldraft">Select Voyage Manifest</label>
                                <select class="selectpicker form-control" id="bldraft" name="voyage_id" data-live-search="true" data-size="10"
                                    title="{{ trans('forms.select') }}">
                                    @foreach ($voyages as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('voyage_id') ? 'selected' : '' }}>{{ $item->voyage_no }}  - {{ optional($item->leg)->name }}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="port">Select Disharge Port</label>
                                <select class="selectpicker form-control" id="discharge_ports" name="discharge_port_id" data-live-search="true" data-size="10"
                                    title="{{ trans('forms.select') }}" disabled>

                                    @foreach($voyages->pluck('voyagePorts')->flatmap(fn($s)=>$s->pluck('port'))->unique()->filter() as $port)
                                        <option class="portOption" value={{ $port->id }}>
                                            {{ $port->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('port_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="port">Select Load Port</label>
                                <select class="selectpicker form-control" id="load_ports" name="load_port_id" data-live-search="true" data-size="10"
                                    title="{{ trans('forms.select') }}" disabled>

                                    @foreach($voyages->pluck('voyagePorts')->flatmap(fn($s)=>$s->pluck('port'))->unique()->filter() as $port)
                                        <option class="portOption" value={{ $port->id }}>
                                            {{ $port->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('port_id')
                                    <div style="color: red;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{ trans('Next') }}</button>
                                <a href="{{ route('xml.index') }}" class="btn btn-danger mt-3">{{ trans('forms.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    $(document).ready(function() {
        $('#bldraft').on('change', function (e) {

            let selectedVoyage = e.target.value

            apiCall(selectedVoyage).then( success => {
                let portIds = success.data.ports;
                addPortsToSelect('load_ports', portIds)
                addPortsToSelect('discharge_ports', portIds)
            })
        })
        $("#load_ports").on('change', function(e) {
            disableAndRefreshSelectPicker("#discharge_ports");
        });

        $("#discharge_ports").on('change', function(e) {
            disableAndRefreshSelectPicker("#load_ports");
        });
    })


    function apiCall(id)
    {
        return axios.get("{{ route('api.get-ports') }}", {
                params:{id: id}
        })
    }
    function addPortsToSelect(selectId, portIds)
    {
        $(`#${selectId} option`).each(function() {
                var optionId = $(this).val();
                if (portIds.includes(+optionId)) {
                    $(this).prop('disabled', false).show();
                } else {
                    $(this).prop('disabled', true).hide();
                }
        });
        document.getElementById(selectId).disabled = false
        $('.selectpicker').selectpicker('refresh');
    }

    function disableAndRefreshSelectPicker(selector) {
    $(selector).prop('disabled', true).selectpicker('refresh');
}

</script>
@endsection
