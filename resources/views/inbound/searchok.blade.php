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
    <script src="{{ asset('js/inbound/searchok.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-body">
                <form method="POST" id="inboundsearch">
                    @csrf
                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('inboundpageLang.delete') !!}">
                    &emsp13;
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('inboundpageLang.download') !!}">

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <inbound-search-table></inbound-search-table>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                </form>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>
@endsection
