@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* for single line table with over-flow , SAP style as asked */
    table {
        table-layout: fixed;
        /* width: 900px; */
    }

    .table-responsive {
        height: 600px;
        overflow: scroll;
    }

    thead tr:nth-child(1) th {
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

</style>
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/download.js') }}"></script>
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
        <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
        <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form id = "notmonthform" method="POST">
                @csrf
                <input type = "hidden" id = "titlename" name = "titlename" value = "非月請購">
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('monthlyPRpageLang.download') !!}">
                    {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!!
                        __('monthlyPRpageLang.return') !!}</button> --}}

                <table class="table">
                    <thead>
                    <tr>
                        <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</td>
                        <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('monthlyPRpageLang.isn') !!}</td>
                        <th><input type = "hidden" id = "title2" name = "title2" value = "品名">{!! __('monthlyPRpageLang.pName') !!}</td>
                        <th><input type = "hidden" id = "title3" name = "title3" value = "請購數量">{!! __('monthlyPRpageLang.buyamount1') !!}</td>
                        <th><input type = "hidden" id = "title4" name = "title4" value = "上傳時間">{!! __('monthlyPRpageLang.uploadtime') !!}</td>
                        <th><input type = "hidden" id = "title5" name = "title5" value = "說明">{!! __('monthlyPRpageLang.description') !!}</td>
                        <th><input type = "hidden" id = "title6" name = "title6" value = "SXB單號">{!! __('monthlyPRpageLang.sxb') !!}</td>
                        <input type = "hidden" id = "titlecount" name = "titlecount" value = "7">
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                    <tr class="isnRows">
                        <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->請購數量}}">{{$data->請購數量}}</td>
                        <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->上傳時間}}">{{$data->上傳時間}}</td>
                        <td><input type = "hidden" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$data->說明}}">{{$data->說明}}</td>
                        <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$data->SXB單號}}">{{$data->SXB單號}}</td>
                    </tr>
                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                    @endforeach

                </tbody>
                </table>


            </form>
        </div>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    </div>
</div>

</html>
@endsection
