@extends('layouts.adminTemplate')

@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection

@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('checkInvLang.check') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <checking-inventory-upload-table></checking-inventory-upload-table>
    </div>
@endsection
