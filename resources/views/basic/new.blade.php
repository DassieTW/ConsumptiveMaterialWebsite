@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/basic/new.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">

        <h2 class="col-auto">{!! __('basicInfoLang.newMats') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
    <div class="card w-100">
        <div class="card-body">
            <form id="newmaterial" class="row gx-6 gy-1 align-items-center">
                @csrf
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.isn') !!}</label>
                    <input class="form-control form-control-lg" type="text" id="number" name="number"
                        placeholder="{!! __('basicInfoLang.enterisn') !!}" required
                        oninput="if(value.length>12)value=value.slice(0,12)" style="width: 200px" />
                    <div id="numbererror" style="display:none; color:red;">{!! __('basicInfoLang.isnrepeat')
                        !!}</div>
                    <div id="numbererror1" style="display:none; color:red;">{!!
                        __('basicInfoLang.isnlength') !!}</div>
                </div>

                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.pName') !!}</label>
                    <input class="form-control form-control-lg" type="text" id="name" name="name"
                        placeholder="{!! __('basicInfoLang.enterpName') !!}" required style="width: 150px" />
                </div>

                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.format') !!}</label>
                    <input class="form-control form-control-lg" type="text" id="format" name="format"
                        placeholder="{!! __('basicInfoLang.enterformat') !!}" required style="width: 150px" />
                </div>

                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.price') !!}</label>
                    <input class="form-control form-control-lg" type="number" id="price" name="price" step="0.00001"
                        placeholder="{!! __('basicInfoLang.enterprice') !!}" required min="0" style="width: 150px" />
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.unit') !!}</label>
                    <input class="form-control form-control-lg " type="text" id="unit" name="unit"
                        placeholder="{!! __('basicInfoLang.enterunit') !!}" required style="width: 150px" />
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.money') !!}</label>
                    <select class="form-select form-select-lg " id="money" name="money" required style="width: 150px">
                        <option style="display: none" disabled selected value="">{!!
                            __('basicInfoLang.entermoney') !!}</option>
                        <option>RMB</option>
                        <option>USD</option>
                        <option>JPY</option>
                        <option>TWD</option>
                        <option>VND</option>
                        <option>IDR</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.mpq') !!}</label>
                    <input class="form-control form-control-lg" type="number" id="mpq" name="mpq"
                        placeholder="{!! __('basicInfoLang.entermpq') !!}" min="0" required style="width: 150px" />
                </div>

                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.moq') !!}</label>
                    <input class="form-control form-control-lg" type="number" id="moq" name="moq"
                        placeholder="{!! __('basicInfoLang.entermoq') !!}" min="0" required style="width: 150px" />
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.lt') !!}</label>
                    <input class="form-control form-control-lg" type="number" id="lt" name="lt"
                        placeholder="{!! __('basicInfoLang.enterlt') !!}" min="0" required style="width: 150px" />
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.gradea') !!}</label>
                    <select class="form-select form-select-lg" id="gradea" name="gradea" required style="width: 150px">
                        <option style="display: none" disabled selected value="">{!!
                            __('basicInfoLang.enteryorn') !!}</option>
                        <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                        <option value="否">{!! __('basicInfoLang.no') !!}</option>
                    </select>
                </div>

                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.belong') !!}</label>
                    <select class="form-select form-select-lg" id="belong" name="belong" required style="width: 150px">
                        <option style="display: none" disabled selected value="">{!!
                            __('basicInfoLang.enterbelong') !!}</option>
                        <option value="單耗">{!! __('basicInfoLang.consume') !!}</option>
                        <option value="站位">{!! __('basicInfoLang.stand') !!}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.month') !!}</label>
                    <select class="form-select form-select-lg" id="month" name="month" required style="width: 150px">
                        <option style="display: none" disabled selected value="">{!!
                            __('basicInfoLang.enteryorn') !!}</option>
                        <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                        <option value="否">{!! __('basicInfoLang.no') !!}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.senddep') !!}</label>
                    <select class="form-select form-select-lg" id="send" name="send" required style="width: 150px">
                        <option style="display: none" disabled selected value="">{!!
                            __('basicInfoLang.entersenddep') !!}</option>
                        @foreach ($data as $data)
                        <option>{{$data->發料部門}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('basicInfoLang.safe') !!}</label>
                    <input class="form-control form-control-lg" type="number" id="safe" name="safe"
                        placeholder="{!! __('basicInfoLang.entersafe') !!}" min="0" style="width: 150px" />
                    <div id="safeerror" style="display:none; color:red;">{!! __('basicInfoLang.safeerror')
                        !!}</div>
                </div>

                <div class="col-auto">
                    <label class="col col-auto form-label"></label>
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.add') !!}">

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
                        <input type="submit" class="btn btn-lg btn-primary"
                            value="{!! __('basicInfoLang.addtodatabase') !!}">
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
                        <form method="post" enctype="multipart/form-data" action="{{ route('basic.uploadmaterial') }}">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <div class="col col-auto ">
                                    <a href="{{asset('download/MaterialExample.xlsx')}}" download>{!!
                                        __('basicInfoLang.exampleExcel') !!}</a>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('basicInfoLang.plz_upload')
                                    !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <div class="col col-auto">
                                    <input class="form-control @error('select_file') is-invalid @enderror" type="file"
                                        name="select_file" />
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

</html>
@endsection
