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
    <script src="{{ asset('js/obound/inboundsearchok.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <form id="inboundsearch" method="POST">
                        @csrf
                        <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                            value="{!! __('oboundpageLang.delete') !!}">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <obound-insearch-table></obound-insearch-table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
