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
        <h2>{!! __('basicInfoLang.newMats') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.matsdata') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "newmaterial">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.isn') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="number" name="number" placeholder="{!! __('basicInfoLang.enterisn') !!}" required/>
                            <div id="numbererror" style="display:none; color:red;">{!! __('basicInfoLang.isnrepeat') !!}</div>
                            <div id="numbererror1" style="display:none; color:red;">{!! __('basicInfoLang.isnlength') !!}</div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.pName') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="name" name="name" placeholder="{!! __('basicInfoLang.enterpName') !!}" required/>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.format') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="format" name="format" placeholder="{!! __('basicInfoLang.enterformat') !!}" required/>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.price') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="price" name="price" step="0.00001" placeholder="{!! __('basicInfoLang.enterprice') !!}" required step="0.0001"/>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.unit') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="unit" name="unit" placeholder="{!! __('basicInfoLang.enterunit') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.money') !!}</label>
                            <select class="form-control form-control-lg " id = "money" name="money" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.entermoney') !!}</option>
                                <option>RMB</option>
                                <option>USD</option>
                                <option>JPY</option>
                                <option>TWD</option>
                                <option>VND</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.mpq') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="mpq" name="mpq" placeholder="{!! __('basicInfoLang.entermpq') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.lt') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="lt" name="lt" placeholder="{!! __('basicInfoLang.enterlt') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.moq') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="moq" name="moq" placeholder="{!! __('basicInfoLang.entermoq') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.gradea') !!}</label>
                            <select class="form-control form-control-lg" id = "gradea" name="gradea" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enteryorn') !!}</option>
                                <option>{!! __('basicInfoLang.yes') !!}</option>
                                <option>{!! __('basicInfoLang.no') !!}</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.belong') !!}</label>
                            <select class="form-control form-control-lg" id = "belong" name="belong" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enterbelong') !!}</option>
                                <option>{!! __('basicInfoLang.consume') !!}</option>
                                <option>{!! __('basicInfoLang.stand') !!}</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.month') !!}</label>
                            <select class="form-control form-control-lg" id = "month" name="month" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enteryorn') !!}</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.senddep') !!}</label>
                            <select class="form-control form-control-lg" id = "send" name="send" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.entersenddep') !!}</option>
                                @foreach ($data as $data)
                                <option>{{$data->發料部門}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('basicInfoLang.safe') !!}</label>
                            <input class="form-control form-control-lgs" type="number" id ="safe" name="safe" placeholder="{!! __('basicInfoLang.entersafe') !!}"/>
                            <div id="safeerror" style="display:none; color:red;">{!! __('basicInfoLang.safeerror') !!}</div>
                        </div>
                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.add') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.return') !!}</button>
                &emsp;
                <a class="btn btn-lg btn-primary" href="{{asset('download/MaterialExample.xlsx')}}" download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                <br><br>


                <form method="post" enctype="multipart/form-data" action = "{{ route('basic.uploadmaterial') }}">
                    @csrf
                    <div class="col-6 col-sm-3">
                        <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                        <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                        @error('select_file')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br>
                        <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
                    </div>
                </form>

            </div>
        </div>




</html>
@endsection
