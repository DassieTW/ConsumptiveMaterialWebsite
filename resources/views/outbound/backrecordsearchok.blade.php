@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.outbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.backrecord') !!}</h3>
            </div>
            <div class="card-body">
                <outbound-backrecord-table></outbound-backrecord-table>
            </div>
        </div>
    </div>
@endsection
