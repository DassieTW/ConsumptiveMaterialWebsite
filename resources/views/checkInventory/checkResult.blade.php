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
        box-shadow: 0px 0px 1px 3px rgba(0, 0, 0, 0.3);
    }

    .sortBtn:hover,
    .sortBtn:focus:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 0, 0, 0.3);
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

    .filterBtn {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 60, 255, 0.1);
    }

    .filterBtn.active:focus:hover,
    .filterBtn:active:focus:hover,
    .filterBtn.active:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 60, 255, 0.3);
    }

    .filterBtn:hover,
    .filterBtn:focus:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 60, 255, 0.3);
    }

    .filterBtn.active,
    .filterBtn:active {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 60, 255, 0.1);
    }

    .filterBtn:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(0, 60, 255, 0.1);
    }

    .filterBtn:active:focus,
    .filterBtn.active:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(0, 60, 255, 0.1);
    }

    .filterBtn2 {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(255, 0, 55, 0.1);
    }

    .filterBtn2.active:focus:hover,
    .filterBtn2:active:focus:hover,
    .filterBtn2.active:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(255, 0, 55, 0.3);
    }

    .filterBtn2:hover,
    .filterBtn2:focus:hover {
        border: none;
        color: rgb(68, 68, 68) !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(255, 0, 55, 0.3);
    }

    .filterBtn2.active,
    .filterBtn2:active {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(255, 0, 55, 0.1);
    }

    .filterBtn2:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: -2px 2px 1px 5px rgba(255, 0, 55, 0.1);
    }

    .filterBtn2:active:focus,
    .filterBtn2.active:focus {
        border: none;
        color: #6c757d !important;
        background-color: #fff;
        box-shadow: 0px 0px 1px 3px rgba(255, 0, 55, 0.1);
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
                                <button type="button"
                                    class="sortBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-sort-up"></i>
                                    <span>{!! __('checkInvLang.loc_short') !!}</span>
                                </button>
                                <div class="w-100 d-block d-sm-none" style="height: 1ch;"></div>
                                <!-- Visible only on xs -->
                                <button type="button"
                                    class="sortBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-sort-up"></i>
                                    <span>{!! __('checkInvLang.isn') !!}</span>
                                </button>
                                <div class="w-100 d-md-none" style="height: 1ch;"></div>
                                <!-- hide on md and wider screens -->
                                <button type="button"
                                    class="filterBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-clipboard-check"></i>
                                    <span>{!! __('checkInvLang.checked') !!}</span>
                                </button>
                                <div class="w-100 d-xl-none" style="height: 1ch;"></div>
                                <!-- hide on md and wider screens -->
                                <button type="button"
                                    class="filterBtn btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-clipboard"></i>
                                    <span>{!! __('checkInvLang.not_checked') !!}</span>
                                </button>
                                <div class="w-100 d-sm-none" style="height: 1ch;"></div>
                                <!-- hide on sm and wider screens -->
                                <div class="w-100 d-none d-xl-block d-xxl-none" style="height: 1ch;"></div>
                                <!-- Visible only on xl -->
                                <button type="button"
                                    class="filterBtn2 btn btn-outline-secondary col col-auto p-1 ms-0 me-1">
                                    <i class="bi bi-clipboard-x"></i>
                                    <span>{!! __('checkInvLang.check_not_right_amount') !!}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body allRecord">
                    <div class="table-responsive">
                        <table class="table table-warning table-hover justify-content-center text-center align-items-center">
                            <thead>
                                <tr id="tableHead" class="justify-content-center align-items-center">
                                    <th class="col col-auto justify-content-center align-items-center px-0 m-0">
                                        <span>{!!
                                            __('checkInvLang.serial_number') !!}</span>
                                    </th>
                                    <th class="col col-auto justify-content-center align-items-center px-0 m-0">
                                        <span>{!!
                                            __('checkInvLang.created_by') !!}</span>
                                    </th>
                                    <th class="col col-auto justify-content-center align-items-center px-0 m-0">
                                        <span>{!!
                                            __('checkInvLang.created_time') !!}</span>
                                    </th>
                                    <th class="col col-auto justify-content-center align-items-center px-0 m-0">
                                        <span>&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="appendDataHere">
                                <tr data-bs-toggle="collapse" data-bs-target="#demo1" aria-expanded="false">
                                    <td>1_2021-03-17_15:29:32</td>
                                    <td>權限1_測試</td>
                                    <td>2021-12-30 19:05:44.470</td>
                                    <td>
                                        <a class="" href="#" disabled style="color: grey;"><i
                                                class="bi bi-chevron-down"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="12" class="p-0 m-0">
                                        <div class="collapse p-0 m-0" id="demo1" aria-expanded="false">
                                            <table class="table table-primary table-hover m-0 p-0">
                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th>Company</th>
                                                        <th>Salary</th>
                                                        <th>Date On</th>
                                                        <th>Date off</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr data-toggle="collapse" class="accordion-toggle"
                                                        data-target="#demo10">
                                                        <td>&nbsp;</td>
                                                        <td>Google</td>
                                                        <td>U$8.00000 </td>
                                                        <td> 2016/09/27</td>
                                                        <td> 2017/09/27</td>
                                                        <td>
                                                            <a class="" href="#" disabled style="color: grey;">
                                                                <i class="bi bi-chevron-down"></i></a>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="12" class="hiddenRow p-0">
                                                            <div class="accordian-body collapse" id="demo10">
                                                                <table class="table table-secondary table-hover m-0 p-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
                                                                            <th>item 1</th>
                                                                            <th>item 2</th>
                                                                            <th>item 3 </th>
                                                                            <th>item 4</th>
                                                                            <th>item 5</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>data 1</td>
                                                                            <td>data 2</td>
                                                                            <td>data 3</td>
                                                                            <td>data 4</td>
                                                                            <td>data 5</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection