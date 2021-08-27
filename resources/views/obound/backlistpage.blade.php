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
        <h2>{!! __('templateWords.obound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.backlist') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('obound.backlist') }}" method="POST">
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('oboundpageLang.backlist') !!}</label>
                            <select class="form-control form-control-lg" id = "list" name="list">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterbacklist') !!}</option>
                            @foreach($data as $data)
                            <option>{{  $data->退料單號 }}</option>
                            @endforeach
                            </select>

                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.enterbacklist') !!}">
                    <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.deletebacklist') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
