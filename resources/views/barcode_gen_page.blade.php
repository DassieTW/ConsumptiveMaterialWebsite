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
@endsection

@section('js')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection


@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3 justify-content-between">
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

        <div class="col-xl-6 col-xxl-12">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <h3 class="card-title mb-0">產生料號條碼</h3>
                </div>
                <div class="card-body pt-2 pb-3">
                    <form class="text-center needs-validation" action="{{ route('barcode_gen') }}" method="post" accept-charset="utf-8" novalidate>
                        @csrf
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-auto p-0 m-0">
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
                            <button class="btn btn-primary col-auto" type="submit">Generate</button>
                        </div>
                    </form>
                    <div class="col-auto">
                        <?php
                        // -------------------------------- file access version of barcode generate -------------------------------
                        //                $oldPath = getcwd(); 
                        //                chdir('barcodeImg');
                        //
                        //                $images = glob("*.bmp");
                        //                foreach ($images as $image) {
                        //                    echo '<img src="http://localhost/web1/barcodeImg/' . $image . '" alt="' . $image . '" />' . "<br>";
                        //                } // foreach
                        //
                        //                chdir($oldPath);
                        // ------------------------------------------------------- end --------------------------------------------------
                        // -------------------------------- web display version of barcode generate -------------------------------
                        if (isset($_POST['barcode1'])) {

                            echo '<img src="' . 'barWebDisplay?' .
                                'barcode1=' . $_POST['barcode1'] .
                                '&barcode2=' . $_POST['barcode2'] .
                                '&pName=' . $_POST['pName'] .
                                '&isIsn=' . 'true' .
                                '&toSess=' . 'true' .
                                '" onerror="this.src=' . "'barWebDisplay?" .
                                'barcode1=' . $_POST['barcode1'] .
                                '&barcode2=' . $_POST['barcode2'] .
                                '&pName=' . $_POST['pName'] .
                                '&isIsn=' . 'true' .
                                '&toSess=' . 'true' . "'"
                                . '"/>';

                            echo '<br>';
                        }
                        // ------------------------------------------------------- end --------------------------------------------------
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection