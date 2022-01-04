@extends('layouts.adminTemplate')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/daterangepicker.css') }}">
<style>
    .sortBtn {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 0, 0, 0.1);
    }
    .sortBtn.active:focus:hover,
    .sortBtn:active:focus:hover,
    .sortBtn.active:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 0, 0, 0.1);
    }

    .sortBtn:hover,
    .sortBtn:focus:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .sortBtn.active,
    .sortBtn:active {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 0, 0, 0.1);
    }
    .sortBtn:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .sortBtn:active:focus,
    .sortBtn.active:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* The animation code */
    @keyframes clickBtn {
        from {
            box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.1);
        }

        to {
            box-shadow: 0px 0px 1px 3px rgba(0, 0, 0, 0.1);
        }
    }

</style>
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
                    <h1 class="card-title" style="color: rgb(71, 71, 71);">{!! __('checkInvLang.time_range') !!}
                        :&nbsp;&nbsp;
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
                    <div class="container m-0 p-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row">
                                    <span class="col col-auto me-0 pe-0">{!! __('checkInvLang.search_by') !!}
                                        &nbsp;</span>
                                    <div class="form-check form-switch col col-auto">
                                        <input class="form-check-input" type="checkbox" id="toggle-state">
                                        <label class="form-check-label" for="toggle-state"
                                            id="toggle-state-text"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <label class="col col-auto col-form-label p-0 m-0">{!! __('checkInvLang.sort_by') !!}
                                    &nbsp;</label>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="input-group input-group-navbar m-0 p-0">
                                    <input type="text" id="texBox" class="form-control" placeholder="Search…"
                                        aria-label="Search">
                                    <button class="btn col col-auto" type="button" id="searchBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-search align-middle">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <button type="button" class="sortBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-sort-up"></i>
                                    <span>{!! __('checkInvLang.loc_short') !!}</span>
                                </button>
                                <button type="button" class="sortBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-sort-up"></i>
                                    <span>{!! __('checkInvLang.isn') !!}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection