@extends('layouts.adminTemplate')
@section('css')
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
    <script src="{{ asset('js/month/download.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>
            <div class="card-body">
                <form id="notmonthform" method="POST">
                    @csrf
                    <input type="hidden" id="titlename" name="titlename" value="非月請購">
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.download') !!}">

                    <notmonth-search-table></notmonth-search-table>

                </form>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>
@endsection
