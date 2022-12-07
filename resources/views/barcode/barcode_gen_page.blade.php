@extends('layouts.adminTemplate')

@section('css')
@endsection

@section('js')
    <script src="{{ asset('/js/barcode/barcode_gen_page.js?v=') . env('APP_VERSION') }}"></script>
@endsection


@section('content')
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <div class="col-auto">
                <h2 class="pb-3">{!! __('templateWords.barcode_generator') !!}</h2>
            </div>

            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block" id="mountingPoint">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="row justify-content-center">
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
                                    <label for="barcode1"
                                        class="col-form-label">{!! __('barcodeGenerator.isn') !!}:&nbsp;&nbsp;</label>
                                </div>
                                <div class="col col-auto p-0 m-0">
                                    <div class="input-group has-validation col col-auto">
                                        <input type="text" id="barcode2" name="barcode2" class="form-control p-1"
                                            maxlength="12" style="width: 17ch; text-align: center;"
                                            placeholder="{!! __('barcodeGenerator.enter_isn') !!}" pattern="[a-zA-Z0-9\-]{12,12}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-3">
                                <div class="col col-auto p-0 m-0">
                                    <label for="isn"
                                        class="col-form-label">{!! __('barcodeGenerator.pName') !!}:&nbsp;&nbsp;</label>
                                </div>
                                <div class="col col-auto p-0">
                                    <input type="text" id="pName" name="pName" class="form-control p-1"
                                        maxlength="15" style="width: 15ch; text-align: center;"
                                        placeholder="{!! __('barcodeGenerator.enter_pName') !!}">
                                    <input type="hidden" name="isIsn" id="isIsn" value="true">
                                    <input type="hidden" name="toSess" id="toSess" value="true">
                                    <input type="hidden" name="fName" id="fName" value="{!! \Session::getId() !!}">
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-3">
                                <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen') !!}</button>
                            </div>
                        </form>

                        @if (\Session::has('imgg') && \Session::get('imgg') === true)
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row justify-content-center align-items-center mt-3">
                                <div id="img-div" class="col-auto">
                                    <span>{{ \Session::get('toSess') }}</span>
                                    <img src="{{ asset('storage/barcodeImg/' . \Session::getId() . '.png') }}"
                                        onerror="this.style.display='none'">
                                    <!-- delete temp file in js -->
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
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
                                    <label for="barcode3"
                                        class="col-form-label">{!! __('barcodeGenerator.loc') !!}:&nbsp;&nbsp;</label>
                                </div>

                                <div class="col col-auto p-0">
                                    <input type="text" name="barcode3" class="form-control p-1" id="barcode3"
                                        maxlength="11" style="width: 11ch; text-align: center;"
                                        placeholder="{!! __('barcodeGenerator.enter_loc') !!}" pattern="[a-zA-Z0-9._%+-]{1,11}" required>

                                    <input type="hidden" name="isIsn" id="isIsn2" value="false">
                                    <input type="hidden" name="toSess" id="toSess2" value="true">
                                    <input type="hidden" name="fName" id="fName2"
                                        value="{!! \Session::getId() !!}">
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-3">
                                <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen') !!}</button>
                            </div>
                        </form>

                        @if (\Session::has('imgg2') && \Session::get('imgg2') === true)
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row justify-content-center align-items-center">
                                <div id="img-div2" class="col-auto">
                                    <img src="{{ asset('storage/barcodeImg/' . \Session::getId() . '-2.png') }}"
                                        onerror="this.style.display='none'">
                                    <!-- delete temp file in js -->
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
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
                                    <label for="batchUp"
                                        class="col-form-label">{!! __('barcodeGenerator.plz_upload') !!}:&nbsp;&nbsp;</label>
                                </div>

                                <div class="col col-auto p-0">
                                    <input type="file" name="batchUp" class="form-control p-1" id="batchUp"
                                        style="text-align: center;" required>

                                    <input type="hidden" name="isIsn" id="isIsn2" value="false">
                                    <input type="hidden" name="toSess" id="toSess2" value="true">
                                    <input type="hidden" name="fName" id="fName2"
                                        value="{!! \Session::getId() !!}">
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-3">
                                <button class="btn btn-primary col-auto" type="submit">{!! __('barcodeGenerator.gen') !!}</button>
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
                            <h6 class="card-title" style="color: orangered">{!! __('barcodeGenerator.Delete_Notice') !!}</h3>
                        </div>
                        <form class="text-center needs-validation" method="post" novalidate>
                            @csrf
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <tbody>
                                        <tr id="tableHead" class="table-primary align-items-center">
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <a id="cleanupISNbtn" style="color: rgb(255, 60, 0);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash3-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                                    </svg>
                                                </a>
                                            </th>
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <span>{!! __('barcodeGenerator.isn') !!}</span>
                                            </th>
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <span>{!! __('barcodeGenerator.print_amount') !!}</span>
                                            </th>
                                        </tr>
                                        {{-- the content here is generated by js --}}
                                        <tr id="tableHead2" class="table-secondary align-items-center">
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <a id="cleanupLOCbtn" style="color: rgb(255, 60, 0);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash3-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                                    </svg>
                                                </a>
                                            </th>
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <span>{!! __('barcodeGenerator.loc') !!}</span>
                                            </th>
                                            <th class="col col-auto align-items-center px-0 m-0">
                                                <span>{!! __('barcodeGenerator.print_amount') !!}</span>
                                            </th>
                                        </tr>
                                        {{-- the content here is generated by js --}}
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="sID" id="sID" value="{!! \Session::getId() !!}">
                            <div class="row justify-content-center align-items-center mt-3">
                                <button class="btn btn-primary col-auto" id="printBtn"
                                    type="submit">{!! __('barcodeGenerator.print') !!}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
