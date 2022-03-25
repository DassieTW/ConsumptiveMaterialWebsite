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
<script src="{{ asset('/js/call/day.js') }}"></script>
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
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
        <h3>{!! __('callpageLang.dayalert') !!}</h3>
        <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" id="test">
                <thead>
                <tr>
                    <th>{!! __('callpageLang.client') !!}</th>
                    <th>{!! __('callpageLang.isn') !!}</th>
                    <th>{!! __('callpageLang.pName') !!}</th>
                    <th>{!! __('callpageLang.format') !!}</th>
                    <th>{!! __('callpageLang.stock') !!}</th>
                    <th>{!! __('callpageLang.days') !!}</th>
                    <th>{!! __('callpageLang.loc') !!}</th>
                    <th>{!! __('callpageLang.mark') !!}</th>

                </tr>
            </thead>
            <tbody>
                @foreach($data as $data)

                <?php

                    $maxtime = date_create(date('Y-m-d',strtotime($data->inventory最後更新時間)));
                    $nowtime = date_create(date('Y-m-d',strtotime(\Carbon\Carbon::now())));
                    $interval = date_diff($maxtime ,$nowtime);
                    $interval = $interval->format('%R%a');
                    $stayday = (int)($interval);
                    $stock = DB::table('inventory')->where('客戶別', $data->客戶別)->where('料號', $data->料號)->sum('現有庫存');
                    $astock = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('現有庫存')->toArray();
                    $astock=array_map(function($v){return round($v,0);}, $astock);
                    $position = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('儲位')->toArray();
                    $test = array_combine($position, $astock);
                    $stock = round($stock , 0);
                ?>

                <tr id="data1{{$loop->index}}" class="isnRows">
                    <td>{{$data->客戶別}}</td>
                    <td>{{$data->料號}}</td>
                    <input type="hidden" id = "data{{$loop->index}}" value="{{$data->料號}}">
                    <td>{{$data->品名}}</td>
                    <td>{{$data->規格}}</td>
                    <td id="stocka{{$loop->index}}">{{$stock}}</td>
                    <td id="staydaya{{$loop->index}}">{{$stayday}}</td>
                    <td>
                        @foreach ($test as $k=> $a)
                        @if ($a > 0)
                        {!! __('callpageLang.loc') !!}:{{$k}} {!! __('callpageLang.nowstock') !!}:{{$a}}<br>
                        @endif
                        @endforeach
                    </td>
                    <td><input class="form-control form-control-lg" type="text" style="width:100px;"></td>
                </tr>

                <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                @endforeach
            </tbody>
            </table>
        </div>
        {{-- <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('call.day')}}'">{!!
            __('callpageLang.return') !!}</button> --}}
    </div>
</div>

</html>
@endsection
