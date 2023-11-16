@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
@endsection

@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="row justify-content-center scrollableWithoutScrollbar">
            <inbound-stock-upload-table></inbound-stock-upload-table>
        </div>
    </div>
@endsection
