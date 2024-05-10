@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/login/usernamechange.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div>
            <div class="row mb-2 mb-xl-3 justify-content-between">
                <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
                <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                    {{-- <vue-bread-crumb></vue-bread-crumb> --}}
                </div>
            </div>
        </div>
        <div class="card">
            <user-table></user-table>
        </div>
    </div>
@endsection
