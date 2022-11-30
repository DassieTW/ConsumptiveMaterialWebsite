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
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/outbound/download.js') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.outbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="backtable" method="POST">
                    @csrf
                    <input type="hidden" id="titlename" name="titlename" value="退料記錄表">
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('outboundpageLang.download') !!}">

                    <outbound-backrecord-table></outbound-backrecord-table>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            </div>
        </div>
    </div>
@endsection
