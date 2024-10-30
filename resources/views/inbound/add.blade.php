@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/tooltip.css?v=') . env('APP_VERSION') }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <inbound-stock-upload-table></inbound-stock-upload-table>
    </div>
@endsection
