@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/basic/new.js') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('basicInfoLang.newMats') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-body">
                <form id="newmaterial" class="row gx-6 gy-1 align-items-center justify-content-between">
                    @csrf
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.isn') !!}</label>
                        <input class="form-control form-control-lg" type="text" id="number" name="number"
                            placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                            style="width: 150px" />
                        <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterisn') !!}</div>
                        <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                            {!! __('basicInfoLang.isnrepeat') !!}</div>
                        <div class="invalid-feedback" id="numbererror2" style="display:none; color:red;">
                            {!! __('basicInfoLang.isnlength') !!}</div>
                    </div>

                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.pName') !!}</label>
                        <input class="form-control form-control-lg" type="text" id="name" name="name"
                            placeholder="{!! __('basicInfoLang.enterpName') !!}" style="width: 150px" />
                        <div class="invalid-feedback" id="nameerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterpName') !!}</div>
                    </div>

                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.format') !!}</label>
                        <input class="form-control form-control-lg" type="text" id="format" name="format"
                            placeholder="{!! __('basicInfoLang.enterformat') !!}" style="width: 150px" />
                        <div class="invalid-feedback" id="formaterror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterformat') !!}</div>
                    </div>

                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.price') !!}</label>
                        <input class="form-control form-control-lg" type="number" id="price" name="price"
                            step="0.00001" placeholder="{!! __('basicInfoLang.enterprice') !!}" min="0" style="width: 150px" />
                        <div class="invalid-feedback" id="priceerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterprice') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.unit') !!}</label>
                        <input class="form-control form-control-lg " type="text" id="unit" name="unit"
                            placeholder="{!! __('basicInfoLang.enterunit') !!}" style="width: 150px" />
                        <div class="invalid-feedback" id="uniterror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterunit') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.money') !!}</label>
                        <select class="form-select form-select-lg " id="money" name="money" style="width: 150px">
                            <option style="display: none" disabled selected value="">{!! __('basicInfoLang.entermoney') !!}</option>
                            <option>RMB</option>
                            <option>USD</option>
                            <option>JPY</option>
                            <option>TWD</option>
                            <option>VND</option>
                            <option>IDR</option>
                        </select>
                        <div class="invalid-feedback" id="moneyerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.entermoney') !!}</div>
                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.mpq') !!}</label>
                        <input class="form-control form-control-lg" type="number" id="mpq" name="mpq"
                            placeholder="{!! __('basicInfoLang.entermpq') !!}" min="0" style="width: 150px" />
                        <div class="invalid-feedback" id="mpqerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.entermpq') !!}</div>

                    </div>

                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.moq') !!}</label>
                        <input class="form-control form-control-lg" type="number" id="moq" name="moq"
                            placeholder="{!! __('basicInfoLang.entermoq') !!}" min="0" style="width: 150px" />
                        <div class="invalid-feedback" id="moqerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.entermoq') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.lt') !!}</label>
                        <input class="form-control form-control-lg" type="number" id="lt" name="lt"
                            placeholder="{!! __('basicInfoLang.enterlt') !!}" min="0" style="width: 150px" />
                        <div class="invalid-feedback" id="lterror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterlt') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.gradea') !!}</label>
                        <select class="form-select form-select-lg" id="gradea" name="gradea" style="width: 150px">
                            <option style="display: none" disabled selected value="">{!! __('basicInfoLang.enteryorn') !!}
                            </option>
                            <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                            <option value="否">{!! __('basicInfoLang.no') !!}</option>
                        </select>
                        <div class="invalid-feedback" id="gradeaerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enteryorn') !!}</div>
                    </div>

                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.belong') !!}</label>
                        <select class="form-select form-select-lg" id="belong" name="belong" style="width: 150px">
                            <option style="display: none" disabled selected value="">{!! __('basicInfoLang.enterbelong') !!}
                            </option>
                            <option value="單耗">{!! __('basicInfoLang.consume') !!}</option>
                            <option value="站位">{!! __('basicInfoLang.stand') !!}</option>
                        </select>
                        <div class="invalid-feedback" id="belongerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enterbelong') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.month') !!}</label>
                        <select class="form-select form-select-lg" id="month" name="month" style="width: 150px">
                            <option style="display: none" disabled selected value="">{!! __('basicInfoLang.enteryorn') !!}
                            </option>
                            <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                            <option value="否">{!! __('basicInfoLang.no') !!}</option>
                        </select>
                        <div class="invalid-feedback" id="montherror" style="display:none; color:red;">
                            {!! __('basicInfoLang.enteryorn') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.senddep') !!}</label>
                        <select class="form-select form-select-lg" id="send" name="send" style="width: 150px">
                            <option style="display: none" disabled selected value="">{!! __('basicInfoLang.entersenddep') !!}
                            </option>
                            @foreach ($data as $data)
                                <option>{{ $data->發料部門 }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="senderror" style="display:none; color:red;">
                            {!! __('basicInfoLang.entersenddep') !!}</div>

                    </div>
                    <div class="col-auto pb-1">
                        <label class="col col-lg-12 form-label p-0 m-0">{!! __('basicInfoLang.safe') !!}</label>
                        <input class="form-control form-control-lg" type="number" id="safe" name="safe"
                            placeholder="{!! __('basicInfoLang.entersafe') !!}" min="0" style="width: 150px" />
                        <div class="invalid-feedback" id="safeerror" style="display:none; color:red;">
                            {!! __('basicInfoLang.safeerror') !!}</div>
                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="row justify-content-center">
                        <input type="submit" class="btn btn-lg btn-primary col col-auto"
                            value="{!! __('basicInfoLang.add') !!}">
                    </div>

                </form>
            </div>
        </div>
        <div class="card w-100" id="materialbody">
            <div class="card-body">
                <form id="materialadd" style="display: none">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="materialaddtable">
                            <tbody id="materialaddbody">
                                <tr>
                                    <th>{!! __('basicInfoLang.delete') !!}</th>
                                    <th>{!! __('basicInfoLang.isn') !!}</th>
                                    <th>{!! __('basicInfoLang.pName') !!}</th>
                                    <th>{!! __('basicInfoLang.format') !!}</th>
                                    <th>{!! __('basicInfoLang.price') !!}</th>
                                    <th>{!! __('basicInfoLang.money') !!}</th>
                                    <th>{!! __('basicInfoLang.unit') !!}</th>
                                    <th>{!! __('basicInfoLang.mpq') !!}</th>
                                    <th>{!! __('basicInfoLang.moq') !!}</th>
                                    <th>{!! __('basicInfoLang.lt') !!}</th>
                                    <th>{!! __('basicInfoLang.month') !!}</th>
                                    <th>{!! __('basicInfoLang.gradea') !!}</th>
                                    <th>{!! __('basicInfoLang.belong') !!}</th>
                                    <th>{!! __('basicInfoLang.senddep') !!}</th>
                                    <th>{!! __('basicInfoLang.safe') !!}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.addtodatabase') !!}">
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('basicInfoLang.upload') !!}</h3>
                </div>

                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class=" w-100">
                            <form method="post" enctype="multipart/form-data"
                                action="{{ route('basic.uploadmaterial') }}">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <div class="col col-auto ">
                                        <a href="{{ asset('download/MaterialExample.xlsx') }}"
                                            download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label p-0 m-0">{!! __('basicInfoLang.plz_upload') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <div class="col col-auto">
                                        <input class="form-control @error('select_file') is-invalid @enderror"
                                            type="file" name="select_file" />
                                        @error('select_file')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="row w-100 justify-content-center">
                                        <div class="col col-auto">
                                            <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                                value="{!! __('basicInfoLang.upload1') !!}">
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
