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

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Barcode Generator</strong> Dashboard</h3>
        </div>

        <div class="col-auto ml-auto text-right mt-n1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                    <li class="breadcrumb-item"><a href="#">Consumables Management Website</a></li>
                    <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Barcode Generator</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-xxl-12">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <div class="float-end">
                        <form class="row g-2">
                            <div class="col-auto">
                                <select class="form-select form-select-sm bg-light border-0">
                                    <option>Jan</option>
                                    <option value="1">Feb</option>
                                    <option value="2">Mar</option>
                                    <option value="3">Apr</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm bg-light rounded-2 border-0" style="width: 100px;" placeholder="Search..">
                            </div>
                        </form>
                    </div>
                    <h5 class="card-title mb-0">Recent Movement</h5>
                </div>
                <div class="card-body pt-2 pb-3">
                    <div class="chart chart-sm">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="chartjs-dashboard-line" style="display: block; height: 250px; width: 605px;" width="756" height="312" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/app.js') }}"></script>
<script src="admin/js/app.js"></script>
