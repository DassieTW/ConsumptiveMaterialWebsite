@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <script src="{{ asset('js/obound/download.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-body">
                <form id="backtable" method="POST">
                    @csrf
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('oboundpageLang.download') !!}">
                    <input type="hidden" id="titlename" name="titlename" value="O庫退料記錄表">

                    <obound-backrecord-table></obound-backrecord-table>

                </form>
            </div>
        </div>
    </div>
@endsection
