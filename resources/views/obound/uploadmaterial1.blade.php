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
        <h2>{!! __('templateWords.obound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.newMats') !!}{!! __('oboundpageLang.upload') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('obound.uploadmaterial') }}">
                            @csrf
                            <div class="">
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
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.new')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
