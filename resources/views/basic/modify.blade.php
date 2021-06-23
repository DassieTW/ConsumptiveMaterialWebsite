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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>料件信息</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">更新料件資料</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('basic.modify') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">料號</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" value = {{ $number }} required/>
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">品名</label>
                            <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id ="name" name="name" value = {{ $name }} required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">規格</label>
                            <input class="form-control form-control-lg @error('format') is-invalid @enderror" type="text" id ="format" name="format" value = {{ $format }} required/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">單價</label>
                            <input class="form-control form-control-lg @error('price') is-invalid @enderror" type="text" id ="price" name="price" value = {{ $price }} required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">單位</label>
                            <input class="form-control form-control-lg @error('unit') is-invalid @enderror" type="text" id ="unit" name="unit" value = {{ $unit }} required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">幣別</label>
                            <select class="form-control form-control-lg @error('money') is-invalid @enderror" id = "money" name="money" class = "@error('money') is-invalid @enderror" required>
                                <option>{{ $money }}</option>
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
                            <input class="form-control form-control-lg @error('mpq') is-invalid @enderror" type="text" id ="mpq" name="mpq" value = {{ $mpq }} required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">LT</label>
                            <input class="form-control form-control-lg @error('lt') is-invalid @enderror" type="text" id ="lt" name="lt" value = {{ $lt }} required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">MOQ</label>
                            <input class="form-control form-control-lg @error('moq') is-invalid @enderror" type="text" id ="moq" name="moq" value = {{ $moq }} required/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">A級資材</label>
                            <select class="form-control form-control-lg @error('gradea') is-invalid @enderror" id = "gradea" name="gradea" class = "@error('gradea') is-invalid @enderror" required>
                                <option>{{ $gradea }}</option>
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
                                <option>{{ $gp }}</option>
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
                                <option>{{ $belong }}</option>
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
                                <option>{{ $month }}</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">發料部門</label>
                            <select class="form-control form-control-lg @error('send') is-invalid @enderror" id = "send" name="send" class = "@error('send') is-invalid @enderror" required>
                                <option>{{ $send }}</option>
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
                            <input class="form-control form-control-lg @error('safe') is-invalid @enderror" type="text" value = "{{ $safe }}" id ="safe" name="safe"/>
                            @error('safe')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="更新">
                </form>
                <br>
                <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">返回</button>
            </div>
        </div>
</html>
@endsection
