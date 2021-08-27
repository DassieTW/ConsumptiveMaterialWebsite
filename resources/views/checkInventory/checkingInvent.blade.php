<?php
if (isset($_SESSION['previous'])) { // check if coming back from barcodePrintingPage
    if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
        unset($_SESSION['locCount']);
        unset($_SESSION['locArray']);
        unset($_SESSION['isnCount']);
        unset($_SESSION['isnArray']);
        unset($_SESSION['isnName']);
        unset($_SESSION['previous']);
    }
}

$_SESSION['previous'] = basename($_SERVER['PHP_SELF']);
?>

@extends('layouts.adminTemplate')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/checkInventory/checkInvent.js') }}"></script>
@endsection


    @section('content')
    <div class="container-fluid p-0">

        <div class="row mb-2 mb-xl-3 justify-content-between">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Checking Inventory</strong> Dashboard</h3>
            </div>

            {{-- this div will not be visible if screen is smaller than lg --}}
            <div class="col-auto ml-auto text-right mt-n1 d-none d-lg-block">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                        <li class="breadcrumb-item"><a href="#">Consumables Management Website</a></li>
                        <li class="breadcrumb-item"><a href="#">盤點管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">盤點</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-xxl-12">
                <div class="card flex-fill w-100">
                    <div class="card-header pb-0">
                        <h3 class="card-title">單號: </h3>
                    </div>
                    <div class="card-body pt-2 pb-3">
                        <p class="message p-0 m-0" style="color: black;">
                            <!-- message added here by js-->
                        </p>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="row ms-auto p-0 m-0">
                            <label class="col col-auto col-form-label p-0 m-0">搜尋：</label>
                            <div class="form-check form-switch col col-auto">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">料號</label>
                            </div>
                        </div>
                        <form class="inp" id="inp" method="post">
                            <input type="text" name="texBox" id="texBox" class="form-control form-control-lg"
                                style="text-align: center;" maxlength="13" autocomplete="off" placeholder="輸入 料號條碼"
                                autofocus>
                            <input type="submit" class="form-control" name="hiddensub" id="hiddensub"
                                style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-xxl-12">
                <div class="card flex-fill w-100">
                    <div class="card-body pt-2 pb-3">
                        <div class="dynamic">
                            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-touch="true"
                                data-bs-ride="carousel" data-bs-interval="false">
                                <div class="carousel-inner">
                                    <!-- things that are generated dynamically added here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection