@foreach($bookingDetails as $key => $item)
    <tr>
        <input type="hidden" value="{{ $item->id }}" name="containerDetails[{{ $key }}][id]">
        <td class="container_type">
            <select class="selectpicker form-control" id="container_type" data-live-search="true" name="containerDetails[{{ $key }}][container_type]" data-size="10" title="{{ trans('forms.select') }}">
                @foreach ($equipmentTypes as $equipmentType)
                    <option value="{{ $equipmentType->id }}" {{ $equipmentType->id == $item->container_type ? 'selected' : '' }}>{{ $equipmentType->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="containerDetailsID">
            <select class="selectpicker form-control" id="containerDetailsID" name="containerDetails[{{ $key }}][container_id]" data-live-search="true" data-size="10" title="{{ trans('forms.select') }}">
                @foreach ($oldContainers as $container)
                    <option value="{{ $container->id }}" {{ $container->id == $item->container_id ? 'selected' : '' }}>{{ $container->code }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" id="qyt" name="containerDetails[{{ $key }}][qty]" class="form-control input" autocomplete="off" placeholder="QTY" value="{{ $item->qty }}" required>
        </td>
        <td>
            <select class="selectpicker form-control" id="activity_location_id" name="containerDetails[{{ $key }}][activity_location_id]" data-live-search="true" data-size="10" title="{{ trans('forms.select') }}">
                @foreach ($activityLocations as $activityLocation)
                    <option value="{{ $activityLocation->id }}" {{ $activityLocation->id == $item->activity_location_id ? 'selected' : '' }}>{{ $activityLocation->code }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" id="seal_no" name="containerDetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No" value="{{ $item->seal_no }}">
        </td>
        <td>
            <input type="text" id="packs" name="containerDetails[{{ $key }}][packs]" class="form-control" autocomplete="off" placeholder="Packs" value="{{ $item->packs }}">
        </td>
        <td>
            <input type="text" name="containerDetails[{{ $key }}][pack_type]" class="form-control" autocomplete="off" placeholder="Pack Type" value="{{ $item->pack_type }}">
        </td>
        <td>
            <input type="text" id="haz" name="containerDetails[{{ $key }}][haz]" class="form-control" autocomplete="off" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" value="{{ $item->haz }}">
        </td>
        <td>
            <input type="text" name="containerDetails[{{ $key }}][net_weight]" class="form-control" autocomplete="off" placeholder="Net Weight" value="{{ $item->net_weight }}">
        </td>
        <td>
            <input type="text" class="form-control" id="weight" name="containerDetails[{{ $key }}][weight]" value="{{ $item->weight }}" placeholder="Weight" autocomplete="off">
        </td>
        <td style="width:85px;">
            <button type="button" class="btn btn-danger remove" onclick="removeItem({{ $item->id }})"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
@endforeach
