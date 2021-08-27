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

@endsection

@section('js')
<script src="{{ asset('/js/barcode/barcode_gen_page.js') }}"></script>
{{-- <script src="{{ asset('js/popupNotice.js') }}"></script> --}}
<!--for notifications pop up -->
@endsection


@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <div class="col-auto">
            <h2 class="pb-3">{!! __('barcodeGenerator.barcode_gening') !!}</h2>
        </div>

        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                    <li class="breadcrumb-item"><a href="#shouldbeDashboard">{!! __('templateWords.websiteName') !!}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">{!! __('templateWords.barcode_gen') !!}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{!! __('templateWords.barcode_generator') !!}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body pt-2 pb-3">
                    <div class="card-header mt-0">
                        <h3 class="card-title">{!! __('barcodeGenerator.isn_gen_barcode') !!}</h3>
                    </div>
                    <form class="text-center needs-validation" id="isnForm" method="post" accept-charset="utf-8"
                        novalidate autocomplete="off">
                        @csrf
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-auto p-0 m-0">
                                <label for="barcode1" class="col-form-label">{!! __('barcodeGenerator.isn')
                                    !!}:&nbsp;&nbsp;</label>
                            </div>
                            <div class="col col-auto p-0 m-0">
                                <div class="input-group has-validation col col-auto">
                                    <input type="text" id="barcode1" name="barcode1" class="form-control p-1"
                                        maxlength="4" style="width: 7ch; text-align: center;" placeholder="1234"
                                        pattern="[0-9A-Za-z]{4,4}" required>
                                    <span class="input-group-text p-1" id=""><strong>-</strong></span>
                                    <input type="text" id="barcode2" name="barcode2" class="form-control p-1"
                                        maxlength="7" style="width: 10ch; text-align: center;" placeholder="12AB345"
                                        pattern="[a-zA-Z0-9]{7,7}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center mt-3">
                            <div class="col col-auto p-0 m-0">
                                <label for="isn" class="col-form-label">{!! __('barcodeGenerator.pName')
                                    !!}:&nbsp;&nbsp;</label>
                            </div>
                            <div class="col col-auto p-0">
                                <input type="text" id="pName" name="pName" class="form-control p-1" maxlength="15"
                                    style="width: 15ch; text-align: center;"
                                    placeholder="{!! __('barcodeGenerator.electric_screwdriver_bits') !!}">
                                <input type="hidden" name="isIsn" id="isIsn" value="true">
                                <input type="hidden" name="toSess" id="toSess" value="true">
                                <input type="hidden" name="fName" id="fName" value="{!! \Session::getId() !!}">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center mt-3">
                            <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen')
                                !!}</button>
                        </div>
                    </form>

                    @if (\Session::has('imgg') && \Session::get('imgg') === true)
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="row justify-content-center align-items-center mt-3">
                        <div id="img-div" class="col-auto">
                            <span>{{\Session::get('toSess')}}</span>
                            <img src="{{asset('storage/barcodeImg/' . \Session::getId() . '.png')}}">
                            <!-- delete temp file in js -->
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body pt-2 pb-3">
                    <div class="card-header mt-0">
                        <h3 class="card-title">{!! __('barcodeGenerator.loc_gen_barcode') !!}</h3>
                    </div>
                    <form class="text-center needs-validation" id="locForm" method="post" accept-charset="utf-8"
                        novalidate autocomplete="off">
                        @csrf
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-auto p-0 m-0">
                                <label for="barcode3" class="col-form-label">{!! __('barcodeGenerator.loc')
                                    !!}:&nbsp;&nbsp;</label>
                            </div>

                            <div class="col col-auto p-0">
                                <input type="text" name="barcode3" class="form-control p-1" id="barcode3" maxlength="11"
                                    style="width: 11ch; text-align: center;" placeholder="7-A000"
                                    pattern="[a-zA-Z0-9._%+-]{1,11}" required>

                                <input type="hidden" name="isIsn" id="isIsn2" value="false">
                                <input type="hidden" name="toSess" id="toSess2" value="true">
                                <input type="hidden" name="fName" id="fName2" value="{!! \Session::getId() !!}">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center mt-3">
                            <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen')
                                !!}</button>
                        </div>
                    </form>

                    @if (\Session::has('imgg2') && \Session::get('imgg2') === true)
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="row justify-content-center align-items-center">
                        <div id="img-div2" class="col-auto">
                            <img src="{{asset('storage/barcodeImg/' . \Session::getId() . '-2.png')}}">
                            <!-- delete temp file in js -->
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body pt-2 pb-3">
                    <div class="card-header mt-0">
                        <h3 class="card-title">{!! __('barcodeGenerator.batch_upload') !!}</h3>
                    </div>


                    <form class="text-center needs-validation" id="fileUpForm" method="post"
                        enctype="multipart/form-data" novalidate autocomplete="off">
                        @csrf
                        <div class="col col-auto pb-3">
                            <a href="{{ asset('download/templateBarcodeGen.xlsx') }}" download>
                                {!! __('barcodeGenerator.exampleExcel') !!}</a>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col col-auto p-0 m-0">
                                <label for="batchUp" class="col-form-label">{!! __('barcodeGenerator.plz_upload')
                                    !!}:&nbsp;&nbsp;</label>
                            </div>

                            <div class="col col-auto p-0">
                                <input type="file" name="batchUp" class="form-control p-1" id="batchUp"
                                    style="text-align: center;" required>

                                <input type="hidden" name="isIsn" id="isIsn2" value="false">
                                <input type="hidden" name="toSess" id="toSess2" value="true">
                                <input type="hidden" name="fName" id="fName2" value="{!! \Session::getId() !!}">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center mt-3">
                            <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen')
                                !!}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card flex-fill w-100">
                <div class="card-body pt-2 pb-3">
                    <div class="card-header mt-0">
                        <h3 class="card-title">{!! __('barcodeGenerator.Temporary_Storage_Zone') !!}</h3>
                    </div>
                    <form class="text-center needs-validation" method="post" novalidate>
                        <table class="table align-items-center">
                            <tbody>
                                <tr id="tableHead" class="table-primary align-items-center">
                                    <th class="col col-auto align-items-center px-0 m-0"><span>&nbsp;</span>
                                    </th>
                                    <th class="col col-auto align-items-center px-0 m-0"><span>{!! __('barcodeGenerator.isn') !!}</span>
                                    </th>
                                    <th class="col col-auto align-items-center px-0 m-0"><span>{!! __('barcodeGenerator.print_amount') !!}</span>
                                    </th>
                                </tr>
                                {{-- the content here is generated by js --}}
                                <tr id="tableHead2" class="table-secondary align-items-center">
                                    <th class="col col-auto align-items-center px-0 m-0"><span>&nbsp;</span>
                                    </th>
                                    <th class="col col-auto align-items-center px-0 m-0"><span>{!! __('barcodeGenerator.loc') !!}</span>
                                    </th>
                                    <th class="col col-auto align-items-center px-0 m-0"><span>{!! __('barcodeGenerator.print_amount') !!}</span>
                                    </th>
                                </tr>
                                {{-- the content here is generated by js --}}
                            </tbody>
                        </table>

                        <div class="row justify-content-center align-items-center mt-3">
                            <button class="btn btn-primary col-auto" id="printBtn" type="submit">{!! __('barcodeGenerator.print')
                                !!}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection