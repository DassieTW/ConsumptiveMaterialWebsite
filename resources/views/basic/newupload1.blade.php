@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.newMats') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
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
                            <div class="">
                                <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                                <input  class="form-control"  type="file" name="select_file" />
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
                            </div>
                        </form>

                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">{!! __('basicInfoLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
