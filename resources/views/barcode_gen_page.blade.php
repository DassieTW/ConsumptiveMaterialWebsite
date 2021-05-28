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
                    <li class="breadcrumb-item"><a href="#">AdminKit</a></li>
                    <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0">ISN</h5>
                                <form class="text-center needs-validation" method="post" accept-charset="utf-8" novalidate>
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col col-auto p-0 mb-1">
                                            <label for="isn" class="col-form-label">料號: &nbsp;&nbsp;</label>
                                        </div>
                                        <div class="col col-auto p-0">
                                            <input type="text" name="barcode1" class="form-control" id="isn" maxlength="4" style="width: 7ch; padding: 1px; border: 1px solid black" placeholder="1234" pattern="[0-9A-Za-z]{4,4}" required autofocus>
                                            <div class="invalid-feedback p-0">
                                                (Enter 4 digits)
                                            </div>
                                        </div>
                                        <div class="col col-auto p-0">
                                            -
                                        </div>
                                        <div class="col-auto p-0">
                                            <input type="text" name="barcode2" class="form-control" maxlength="7" style="width: 10ch; padding: 1px; border: 1px solid black" placeholder="12AB345" pattern="[a-zA-Z0-9]{7,7}" required autofocus>
                                            <div class="invalid-feedback">
                                                (Enter 7 digits)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col col-auto p-0 m-0">
                                            <label for="isn" class="col-form-label">品名: &nbsp;&nbsp;</label>
                                        </div>
                                        <div class="col col-auto p-0">
                                            <input type="text" name="pName" class="form-control" id="pName" maxlength="15" style="width: 15ch; padding: 1px; border: 1px solid black" placeholder="電動起子頭" required>
                                            <div class="invalid-feedback p-0">
                                                (Enter a name)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center align-items-center">
                                        <br>
                                        <br>
                                        <br>
                                        <button class="btn btn-primary col-auto" type="submit">Generate</button>
                                    </div>
                                </form>
                                <!-- <div class="mb-1">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span>
                                    <span class="text-muted">Since last week</span>
                                </div> -->
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Visitors</h5>
                                <h1 class="mt-1 mb-3">14.212</h1>
                                <div class="mb-1">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 5.25% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Earnings</h5>
                                <h1 class="mt-1 mb-3">$21.300</h1>
                                <div class="mb-1">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 6.65% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Orders</h5>
                                <h1 class="mt-1 mb-3">64</h1>
                                <div class="mb-1">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -2.25% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/app.js') }}"></script>
<script src="admin/js/app.js"></script>