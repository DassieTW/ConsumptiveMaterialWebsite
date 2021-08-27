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
>>>>>>> 0827tony
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
<<<<<<< HEAD
    <body>
        <main class="d-flex w-100 h-100">
        <form action="{{ route('basic.searchmaterial') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">料號查詢</label>
                <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="Enter material number" required/>
                @error('number')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="text-center mt-3">
                <input type = "submit" class="btn btn-lg btn-primary" value="Search">
                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                <br>
                <br>
                <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('basic.index') }}">返回</a>
            </div>
        </form>
        </main>
    </body>
=======
        <h2>{!! __('basicInfoLang.newMats') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.matsInfo') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                    <form action = "{{ route('basic.searchmaterial') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{!! __('basicInfoLang.matssearch') !!}</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="{!! __('basicInfoLang.enterisn') !!}"/>
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="text-center mt-3">
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.matssearch') !!}">
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.return') !!}</button>
                    </div>
                    </div>
                </div>

        </div>
    </div>
>>>>>>> 0827tony
</html>
@endsection
