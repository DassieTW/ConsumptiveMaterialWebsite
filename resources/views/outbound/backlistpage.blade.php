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
        <h2>{!! __('templateWords.outbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.backlist') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('outbound.backlist') }}" method="POST">
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('outboundpageLang.backlist') !!}</label>
                            <select class="form-control form-control-lg" id = "list" name="list">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterbacklist') !!}</option>
                            @foreach($data as $data)
                            <option>{{  $data->退料單號 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.senddep') !!}</label>
                                    <select class="form-control form-control-lg" id = "send" name="send">
                                    <option style="display: none" disabled selected>{!! __('outboundpageLang.entersenddep') !!}</option>
                                    <option>IE備品室</option>
                                    <option>ME備品室</option>
                                    <option>設備備品室</option>
                                    <option>備品室</option>
                                    </select>
                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.searchbacklist') !!}">
                    <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.deletebacklist') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.index')}}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
