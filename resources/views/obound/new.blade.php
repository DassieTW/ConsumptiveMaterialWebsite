@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/obound/new.js') }}"></script>
@endsection
@section('content')

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.obound') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.newMats') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form id="newmaterial">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">
                        <label class="col col-auto form-label">{!! __('oboundpageLang.oisn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                type="text" id="number" name="number" required
                                placeholder="{!! __('oboundpageLang.enterisn') !!}">
                            <div id="numbererror" style="display:none; color:red;">{!! __('oboundpageLang.isnrepeat')
                                !!}</div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.pName') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text"
                                id="name" name="name"  required
                                placeholder="{!! __('oboundpageLang.enterpName') !!}">
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.format') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <input class="form-control form-control-lg @error('format') is-invalid @enderror"
                                type="text" id="format" name="format" required
                                placeholder="{!! __('oboundpageLang.enterformat') !!}">

                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" class="btn btn-lg btn-primary"
                                    value="{!! __('oboundpageLang.addtodatabase') !!}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <div class="row justify-content-center">
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.upload') !!}</h3>
            </div>

            <div class="row justify-content-center">
                <div class="card-body">
                    <div class=" w-100">
                        <form method="post" enctype="multipart/form-data" action="{{ route('obound.uploadmaterial') }}">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <div class="col col-auto ">
                                    <a href="{{asset('download/OMaterialExample.xlsx')}}" download>{!!
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
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>

</html>
@endsection
