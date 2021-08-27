@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<<<<<<< HEAD
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
=======
<!--for this page's sepcified js -->
<script src="{{ asset('js/basic/new.js') }}"></script>
>>>>>>> 0827tony
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
<<<<<<< HEAD
        <h2>新增料件</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">料件資料</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('basic.new') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">料號</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="Enter 料號" required/>
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">品名</label>
                            <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id ="name" name="name" placeholder="Enter 品名" required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">規格</label>
                            <input class="form-control form-control-lg @error('format') is-invalid @enderror" type="text" id ="format" name="format" placeholder="Enter 規格" required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">單價</label>
                            <input class="form-control form-control-lg @error('price') is-invalid @enderror" type="text" id ="price" name="price" placeholder="Enter 單價" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">單位</label>
                            <input class="form-control form-control-lg @error('unit') is-invalid @enderror" type="text" id ="unit" name="unit" placeholder="Enter 單位" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">幣別</label>
                            <select class="form-control form-control-lg @error('money') is-invalid @enderror" id = "money" name="money" class = "@error('money') is-invalid @enderror" required>
                                <option>選擇幣別</option>
                                <option>RMB</option>
                                <option>USD</option>
                                <option>JPY</option>
                            </select>
                            @error('money')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">MPQ</label>
                            <input class="form-control form-control-lg @error('mpq') is-invalid @enderror" type="text" id ="mpq" name="mpq" placeholder="Enter MPQ" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">LT</label>
                            <input class="form-control form-control-lg @error('lt') is-invalid @enderror" type="text" id ="lt" name="lt" placeholder="Enter LT" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">MOQ</label>
                            <input class="form-control form-control-lg @error('moq') is-invalid @enderror" type="text" id ="moq" name="moq" placeholder="Enter MOQ" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">A級資材</label>
                            <select class="form-control form-control-lg @error('gradea') is-invalid @enderror" id = "gradea" name="gradea" class = "@error('gradea') is-invalid @enderror" required>
                                <option>是/否</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                            @error('gradea')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">GP料件</label>
                            <select class="form-control form-control-lg @error('gp') is-invalid @enderror" id = "gp" name="gp" class = "@error('gp') is-invalid @enderror" required>
                                <option>是/否</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                            @error('gp')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">耗材歸屬</label>
                            <select class="form-control form-control-lg @error('belong') is-invalid @enderror" id = "belong" name="belong" class = "@error('belong') is-invalid @enderror" required>
                                <option>選擇耗材歸屬</option>
                                <option>單耗</option>
                                <option>站位</option>
                            </select>
                            @error('belong')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">月請購</label>
                            <select class="form-control form-control-lg @error('month') is-invalid @enderror" id = "month" name="month" class = "@error('month') is-invalid @enderror" required>
                                <option>是/否</option>
=======
        <h2>{!! __('basicInfoLang.newMats') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.matsdata') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "newmaterial">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.isn') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="number" name="number" placeholder="{!! __('basicInfoLang.enterisn') !!}" required/>
                            <div id="numbererror">{!! __('basicInfoLang.isnrepeat') !!}</div>
                            <div id="numbererror1">{!! __('basicInfoLang.isnlength') !!}</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.pName') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="name" name="name" placeholder="{!! __('basicInfoLang.enterpName') !!}" required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.format') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="format" name="format" placeholder="{!! __('basicInfoLang.enterformat') !!}" required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.price') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="price" name="price" placeholder="{!! __('basicInfoLang.enterprice') !!}" required step="0.0001"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.unit') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="unit" name="unit" placeholder="{!! __('basicInfoLang.enterunit') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-6">
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
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.mpq') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="mpq" name="mpq" placeholder="{!! __('basicInfoLang.entermpq') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.lt') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="lt" name="lt" placeholder="{!! __('basicInfoLang.enterlt') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.moq') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="moq" name="moq" placeholder="{!! __('basicInfoLang.entermoq') !!}" required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.gradea') !!}</label>
                            <select class="form-control form-control-lg" id = "gradea" name="gradea" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enteryorn') !!}</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.belong') !!}</label>
                            <select class="form-control form-control-lg" id = "belong" name="belong" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enterbelong') !!}</option>
                                <option>單耗</option>
                                <option>站位</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.month') !!}</label>
                            <select class="form-control form-control-lg" id = "month" name="month" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.enteryorn') !!}</option>
>>>>>>> 0827tony
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
<<<<<<< HEAD
                            <label class="form-label">發料部門</label>
                            <select class="form-control form-control-lg @error('send') is-invalid @enderror" id = "send" name="send" class = "@error('send') is-invalid @enderror" required>
                                <option>選擇發料部門</option>
                                <option>IE備品室</option>
                                <option>ME備品室</option>
                                <option>設備備品室</option>
                            </select>
                            @error('send')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">安全庫存</label>
                            <input class="form-control form-control-lg @error('safe') is-invalid @enderror" type="text" id ="safe" name="safe" placeholder="Enter 安全庫存"/>
                            @error('safe')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="新增">
                </form>
                <br>
                <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('basic.index') }}">返回</a>
            </div>
        </div>
=======
                            <label class="form-label">{!! __('basicInfoLang.senddep') !!}</label>
                            <select class="form-control form-control-lg" id = "send" name="send" required>
                                <option style="display: none" disabled selected value = "">{!! __('basicInfoLang.entersenddep') !!}</option>
                                <option>IE備品室</option>
                                <option>ME備品室</option>
                                <option>設備備品室</option>
                                <option>備品室</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{!! __('basicInfoLang.safe') !!}</label>
                            <input class="form-control form-control-lgs" type="text" id ="safe" name="safe" placeholder="{!! __('basicInfoLang.entersafe') !!}"/>
                            <div id="safeerror">{!! __('basicInfoLang.safeerror') !!}</div>
                        </div>
                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.add') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.return') !!}</button>
                &emsp;
                <a class="btn btn-lg btn-primary" href="{{asset('download/MaterialExample.xlsx')}}" download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                <br><br>

                @if(count($errors) > 0)
                <div class="alert alert-danger">
                    Upload Validation Error<br><br>
                    <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                <form method="post" enctype="multipart/form-data" action = "{{ route('basic.uploadmaterial') }}">
                    @csrf
                    <div class="col-6 col-sm-3">
                        <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                        <input  class="form-control"  type="file" name="select_file" />
                        <br>
                        <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
                    </div>
                </form>

            </div>
        </div>




>>>>>>> 0827tony
</html>
@endsection
