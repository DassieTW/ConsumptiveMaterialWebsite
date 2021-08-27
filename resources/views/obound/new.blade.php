
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
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.newMats') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "newmaterial">
                    @csrf
                    <div class="mb-3">

                        <label class="form-label">{!! __('oboundpageLang.oisn') !!}</label>
                        <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" style="width: 250px" required>
                        <div id="numbererror">{!! __('oboundpageLang.isnrepeat') !!}</div>

                        <label class="form-label">{!! __('oboundpageLang.pName') !!}</label>
                        <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id ="name" name="name" style="width: 250px" required>


                        <label class="form-label">{!! __('oboundpageLang.format') !!}</label>
                        <input class="form-control form-control-lg @error('format') is-invalid @enderror" type="text" id ="format" name="format" style="width: 250px" required>

                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.addtodatabase') !!}">
                </form>
                    <br>
                    <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
                    &emsp;
                    <a class="btn btn-lg btn-primary" href="{{asset('download/OMaterialExample.xlsx')}}" download>{!! __('oboundpageLang.exampleExcel') !!}</a>
                    <br><br>

                    <form method="post" enctype="multipart/form-data" action = "{{ route('obound.uploadmaterial') }}">
                        @csrf
                        <div class="col-6 col-sm-3">
                            <label>{!! __('oboundpageLang.plz_upload') !!}</label>
                            <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                            @error('select_file')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <br>
                            <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.upload') !!}">
                        </div>
                    </form>

                </div>
            </div>




    </html>
    @endsection

