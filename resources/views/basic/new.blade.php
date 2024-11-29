@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
@endsection

@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('basicInfoLang.newMats') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <mats-info-upload-table></mats-info-upload-table>
    </div>
@endsection
