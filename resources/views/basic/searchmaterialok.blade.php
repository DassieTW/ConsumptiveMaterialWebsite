@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* hide scrollbar but still scrollable */
    .scrollableWithoutScrollbar {
        -ms-overflow-style: none !important;
        /* IE and Edge */
        scrollbar-width: none !important;
        /* FireFox */
    }

    .scrollableWithoutScrollbar::-webkit-scrollbar {
        /* Chrome, Safari and Opera */
        display: none !important;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('js/basic/material.js') }}"></script>
<!--for this page's sepcified js -->
@endsection
@section('content')
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">

        <h2 class="col-auto">{!! __('basicInfoLang.basicInfo') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="materialsearch" method="POST">
                @csrf
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.delete') !!}">
                &emsp13;
                <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.change') !!}">
                &emsp13;
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.download') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <basic-info-table></basic-info-table>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </form>
        </div>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    </div>
</div>
@endsection
