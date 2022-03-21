@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <script src="{{ asset('js/basic/material.js') }}"></script>
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('basicInfoLang.matssearch') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">

            <form id="materialsearch" method="POST">
                @csrf
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.delete') !!}">
                <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.change') !!}">
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.download') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div id="mountingPoint">
                    <basic-info-table></basic-info-table>
                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('basic.material') }}'">{!! __('basicInfoLang.return') !!}</button>
        </div>
    </div>

    </html>
@endsection
