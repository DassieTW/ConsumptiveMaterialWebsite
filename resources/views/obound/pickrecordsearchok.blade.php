@extends('layouts.adminTemplate')
@section('css')
    <style>
        .scrollableWithoutScrollbar::-webkit-scrollbar-track {
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            border-radius: 4px;
            background-color: #F5F5F5;
        }

        .scrollableWithoutScrollbar::-webkit-scrollbar {
            height: 4px;
            -webkit-appearance: none;
        }

        .scrollableWithoutScrollbar::-webkit-scrollbar-thumb {
            border-radius: 4px;
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('js/obound/download.js?v=') . env('APP_VERSION') }}"></script>
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
                <form id="picktable" method="POST">
                    @csrf

                    <input type="hidden" id="titlename" name="titlename" value="O庫領料記錄表">
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('oboundpageLang.download') !!}">

                    <obound-pickrecord-table></obound-pickrecord-table>

                </form>
            </div>
        </div>
    </div>
@endsection
