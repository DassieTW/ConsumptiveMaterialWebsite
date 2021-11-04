@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('callpageLang.callsys') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('callpageLang.safealert') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form action="{{ route('call.safesubmit') }}" method="POST">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">

                        <label class="col col-auto form-label">{!! __('callpageLang.senddep') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="send" name="send">
                                <option style="display: none" disabled selected>{!! __('callpageLang.entersenddep') !!}
                                </option>
                                @foreach($sends as $send)
                                <option>{{ $send->發料部門 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" id="" name="" class="btn btn-lg btn-primary"
                                    value="{!! __('callpageLang.change') !!}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
