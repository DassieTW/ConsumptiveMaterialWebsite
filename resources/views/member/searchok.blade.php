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
        <h2>人員信息</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">更新人員信息</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('member.changenumber') }}" method="POST">
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">工號</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" value = {{ $number }} required/>
                            <label class="form-label">姓名</label>
                            <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id ="name" name="name" value = {{ $name }} required/>
                            <label class="form-label">部門</label>
                            <input class="form-control form-control-lg @error('department') is-invalid @enderror" type="text" id ="department" name="department" value = {{ $department }} required/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">更新</button>
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.search')}}'">返回</button>
            </div>
        </div>
</html>
@endsection
