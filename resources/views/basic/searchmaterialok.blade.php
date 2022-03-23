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
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
    <div id="mountingPoint">
        {{-- <vue-bread-crumb></vue-bread-crumb> --}}
        <div class="card">
            <div class="card-body">
                <form id="materialsearch" method="POST">
                    @csrf
                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.delete') !!}">
                    <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.change') !!}">
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.download') !!}">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <basic-info-table></basic-info-table>
                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>

    </html>
@endsection
