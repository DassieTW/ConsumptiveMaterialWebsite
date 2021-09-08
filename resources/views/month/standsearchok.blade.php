@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/standsearch.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.standUpdate') !!}</h3>
            </div>
            <div class="card-body">

                        <form action="{{ route('month.standchangeordelete') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "站位人力">
                            <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                                <th><input type = "hidden" name = "title0" value = "料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                <th><input type = "hidden" name = "title1" value = "品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                <th><input type = "hidden" name = "title2" value = "單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                <th><input type = "hidden" name = "title3" value = "MPQ">{!! __('monthlyPRpageLang.mpq') !!}</th>
                                <th><input type = "hidden" name = "title4" value = "LT">{!! __('monthlyPRpageLang.lt') !!}</th>
                                <th><input type = "hidden" name = "title5" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                <th><input type = "hidden" name = "title6" value = "機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                <th><input type = "hidden" name = "title7" value = "製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                <th><input type = "hidden" name = "title8" value = "當月站位人數">{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                <th><input type = "hidden" name = "title9" value = "當月開線數">{!! __('monthlyPRpageLang.nowline') !!}</th>
                                <th><input type = "hidden" name = "title10" value = "當月開班數">{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                <th><input type = "hidden" name = "title11" value = "當月每人每日需求量">{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                <th><input type = "hidden" name = "title12" value = "當月每日更換頻率">{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                <th><input type = "hidden" name = "title13" value = "當月每日需求">{!! __('monthlyPRpageLang.nowdayneed') !!}</th>
                                <th><input type = "hidden" name = "title14" value = "下月站位人數">{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                <th><input type = "hidden" name = "title15" value = "下月開線數">{!! __('monthlyPRpageLang.nextline') !!}</th>
                                <th><input type = "hidden" name = "title16" value = "下月開班數">{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                <th><input type = "hidden" name = "title17" value = "下月每人每日需求量">{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                <th><input type = "hidden" name = "title18" value = "下月每日更換頻率">{!! __('monthlyPRpageLang.nextchange') !!}</th>
                                <th><input type = "hidden" name = "title19" value = "下月每日需求">{!! __('monthlyPRpageLang.nextdayneed') !!}</th>
                                <th><input type = "hidden" name = "title20" value = "安全庫存">{!! __('monthlyPRpageLang.safestock') !!}</th>
                                <input type = "hidden" name = "time" value = "21">
                            </tr>
                                @foreach($data as $data)

                                <tr id= "{{$loop->index}}">
                                    <td><input type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "name{{$loop->index}}" name = "name{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "unit{{$loop->index}}" name = "unit{{$loop->index}}" value = "{{$data->單位}}">{{$data->單位}}</td>
                                    <td><input type = "hidden" id = "mpq{{$loop->index}}" name = "mpq{{$loop->index}}" value = "{{$data->MPQ}}">{{$data->MPQ}}</td>
                                    <td><input type = "hidden" id = "lt{{$loop->index}}" name = "lt{{$loop->index}}" value = "{{$data->LT}}">{{$data->LT}}</td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "machine{{$loop->index}}" name = "machine{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "production{{$loop->index}}" name = "production{{$loop->index}}" value = "{{$data->製程}}">{{$data->製程}}</td>
                                    <td><input style="width:80px" type = "number" id = "nowpeople{{$loop->index}}" name = "nowpeople{{$loop->index}}" value = "{{$data->當月站位人數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nowline{{$loop->index}}" name = "nowline{{$loop->index}}" value = "{{$data->當月開線數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nowclass{{$loop->index}}" name = "nowclass{{$loop->index}}" value = "{{$data->當月開班數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nowuse{{$loop->index}}" name = "nowuse{{$loop->index}}" value = "{{$data->當月每人每日需求量}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nowchange{{$loop->index}}" name = "nowchange{{$loop->index}}" value = "{{$data->當月每日更換頻率}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nowneed{{$loop->index}}" name = "nowneed{{$loop->index}}" readonly></td>
                                    <td><input style="width:80px" type = "number" id = "nextpeople{{$loop->index}}" name = "nextpeople{{$loop->index}}" value = "{{$data->下月站位人數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nextline{{$loop->index}}" name = "nextline{{$loop->index}}" value = "{{$data->下月開線數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nextclass{{$loop->index}}" name = "nextclass{{$loop->index}}" value = "{{$data->下月開班數}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nextuse{{$loop->index}}" name = "nextuse{{$loop->index}}" value = "{{$data->下月每人每日需求量}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nextchange{{$loop->index}}" name = "nextchange{{$loop->index}}" value = "{{$data->下月每日更換頻率}}"></td>
                                    <td><input style="width:80px" type = "number" id = "nextneed{{$loop->index}}" name = "nextneed{{$loop->index}}" readonly></td>
                                    <td><input style="width:80px" type = "number" id = "safe{{$loop->index}}" name = "safe{{$loop->index}}" readonly></td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                        </div>
                            @foreach ($data1 as $data )

                                    <input type = "hidden" name = "data0{{$loop->index}}" value = "{{$data->料號}}">
                                    <input type = "hidden" name = "data1{{$loop->index}}" value = "{{$data->品名}}">
                                    <input type = "hidden" name = "data2{{$loop->index}}" value = "{{$data->單位}}">
                                    <input type = "hidden" name = "data3{{$loop->index}}" value = "{{$data->MPQ}}">
                                    <input type = "hidden" name = "data4{{$loop->index}}" value = "{{$data->LT}}">
                                    <input type = "hidden" name = "data5{{$loop->index}}" value = "{{$data->客戶別}}">
                                    <input type = "hidden" name = "data6{{$loop->index}}" value = "{{$data->機種}}">
                                    <input type = "hidden" name = "data7{{$loop->index}}" value = "{{$data->製程}}">
                                    <input type = "hidden" id="data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data14{{$loop->index}}" name = "data14{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data15{{$loop->index}}" name = "data15{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data16{{$loop->index}}" name = "data16{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data17{{$loop->index}}" name = "data17{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data18{{$loop->index}}" name = "data18{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data19{{$loop->index}}" name = "data19{{$loop->index}}" value = "">
                                    <input type = "hidden" id="data20{{$loop->index}}" name = "data20{{$loop->index}}" value = "">
                            @endforeach
                            <br>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.delete') !!}">
                            <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.change') !!}">
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.download') !!}">
                        </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.stand')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
