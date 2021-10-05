@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/uploadconsume.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.monthly') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-50">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadnotmonth') }}">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">

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
                                        value="{!! __('monthlyPRpageLang.upload') !!}">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <button class="btn btn-lg btn-primary"
                        onclick="location.href='{{route('month.importnotmonth')}}'">{!!
                        __('monthlyPRpageLang.return') !!}</button>
                </div>
            </div>
        </div>
    </div>

</html>
@endsection
