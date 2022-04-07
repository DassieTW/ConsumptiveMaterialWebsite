@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
</div><div class="row justify-content-center">
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.upload') !!}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form method="post" enctype="multipart/form-data" action="{{ route('obound.uploadinventory') }}">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <div class="col col-auto ">
                                <a href="{{asset('download/OboundStockExample.xlsx')}}" download>{!!
                                    __('oboundpageLang.exampleExcel') !!}</a>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('oboundpageLang.plz_upload') !!}</label>
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
                                        value="{!! __('oboundpageLang.upload1') !!}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
