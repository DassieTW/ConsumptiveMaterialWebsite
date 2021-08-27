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
        <h2>{!! __('templateWords.monthly') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.SRM') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('month.srmsearch') }}" method="POST" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client">
                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="number" name="number">

                            <label class="form-label">{!! __('monthlyPRpageLang.srm') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="srm" name="srm">

                            <label class="form-label">{!! __('monthlyPRpageLang.senddep') !!}</label>
                            <select class="form-control form-control-lg" id = "send" name="send">
                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entersenddep') !!}</option>
                            <option>IE備品室</option>
                            <option>ME備品室</option>
                            <option>設備備品室</option>
                            <option>備品室</option>
                            </select>

                        </div>
                    </div>
                    <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.search') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.index')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
