<?php
    $col = isset($width) ? $width : '6';
?>
<div class="form-group col-md-{{$col}}">
    <label for="companyInput">{{trans('item_nature.company')}} *</label>
    <select class="selectpicker show-tick form-control" id="companyInput" name="company_id" title="{{trans('forms.select')}}">
        @if(count($user_companies) ==1)
            @foreach ($user_companies as $company)
            <option value="{{$company->id}}" selected>{{$company->name}}</option>
            @endforeach
        @else
            @foreach ($user_companies as $company)
            <option value="{{$company->id}}" {{$company->id == old('company_id',optional($model)->company_id) ?'selected' :''}}>{{$company->name}}</option>
            @endforeach
        @endif
    </select>
    @error('company_id')
    <div class="invalid-feedback">
        {{$message}}
    </div>
    @enderror
</div>
