@extends('layouts.app')

@section('content')
@include('bldraft.bldraft._modal_show_containers')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Bl Draft</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">BL Prints Counter</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                
                <div class="widget-content widget-content-area ">
                    <form method="GET" action="{{ route('bldraft.printcounter') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="query" class="form-control" placeholder="Search by BL Draft Ref No" value="{{ old('query', $query) }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>

                    @if(isset($blDrafts) && count($blDrafts) > 0)
                    <form method="POST" action="{{ route('bldraft.updatePrintCounter') }}">
                        @csrf
                        <input type="hidden" name="query" value="{{ $query }}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 60%;">BL Draft Ref No</th>
                                    <th style="width: 25%;">Print Counter</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blDrafts as $blDraft)
                                <tr>
                                    <td>{{ $blDraft->ref_no }}</td>
                                    <td>
                                        <input type="number" name="print_count[{{ $blDraft->bl_draft_id }}]" class="form-control" value="{{ $blDraft->print_count ?? 0 }}" min="0">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    @else
                    <p>No results found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
