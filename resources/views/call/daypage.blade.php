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
        <div class="card">
            <div class="card-header">
                <h3>{!! __('callpageLang.day') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('call.daysubmit') }}" method="POST">
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">


                            <label class="form-label">{!! __('callpageLang.senddep') !!}</label>
                                    <select class="form-control form-control-lg" id = "send" name="send">
                                    <option style="display: none" disabled selected>{!! __('callpageLang.entersenddep') !!}</option>
                                    <option>IE備品室</option>
                                    <option>ME備品室</option>
                                    <option>設備備品室</option>
                                    <option>備品室</option>
                                    </select>

                        </div>
                    </div>
                    <input type = "submit"  id = "" name = "" class="btn btn-lg btn-primary" value="{!! __('callpageLang.change') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('call.index')}}'">{!! __('callpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
