@extends('layouts.adminTemplate')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/daterangepicker.css') }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/checkInventory/checkResult.js') }}"></script>
<script src="{{ asset('/js/daterangepicker.js') }}"></script>
@endsection


@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3 justify-content-between">
        <div class="col-auto">
            <h2 class="pb-3">{!! __('checkInvLang.check_result') !!}</h2>
        </div>

        {{-- this div will not be visible if screen is smaller than lg --}}
        <div class="col-auto ml-auto text-right mt-n1 d-none d-lg-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                    <li class="breadcrumb-item"><a href="#">{!! __('templateWords.websiteName') !!}</a></li>
                    <li class="breadcrumb-item"><a href="#">{!! __('checkInvLang.page_name') !!}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{!! __('checkInvLang.check_result') !!}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-header pb-0">
                    <h1 class="card-title" style="color: rgb(71, 71, 71);">{!! __('checkInvLang.serial_number') !!} :&nbsp;&nbsp;
                        <div class="btn-group col col-auto">
                            <div id="reportrange" class="pull-right">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span id="DateRangeString"></span> <b class="caret"></b>
                            </div>
                        </div>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="row ms-auto p-0 m-0">
                        <label class="col col-auto col-form-label p-0 m-0">{!! __('checkInvLang.search')
                            !!}：</label>
                        <div class="form-check form-switch col col-auto">
                            <input class="form-check-input" type="checkbox" id="toggle-state">
                            <label class="form-check-label" for="toggle-state" id="toggle-state-text"></label>
                        </div>
                    </div>
                    <form class="inp text-center needs-validation" id="inp" method="post" novalidate autocomplete="off">
                        @csrf
                        <input type="text" id="texBox" name="texBox" class="form-control form-control-lg"
                            style="text-align: center;" autocomplete="off" autofocus>
                        <input type="submit" class="form-control" name="hiddensub" id="hiddensub"
                            style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection