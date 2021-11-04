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
    <div id="url"></div>
    <div class="card-body" id="standbody">

        <form id="stand" method="POST">
            @csrf
            <input type="hidden" id="titlename" name="titlename" value="站位人力">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>{!! __('monthlyPRpageLang.check') !!}</th>
                        <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('monthlyPRpageLang.isn')
                            !!}</th>
                        <th><input type="hidden" id="title1" name="title1" value="品名">{!! __('monthlyPRpageLang.pName')
                            !!}</th>
                        <th><input type="hidden" id="title2" name="title2" value="單位">{!! __('monthlyPRpageLang.unit')
                            !!}</th>
                        <th><input type="hidden" id="title3" name="title3" value="MPQ">{!! __('monthlyPRpageLang.mpq')
                            !!}</th>
                        <th><input type="hidden" id="title4" name="title4" value="LT">{!! __('monthlyPRpageLang.lt') !!}
                        </th>
                        <th><input type="hidden" id="title5" name="title5" value="客戶別">{!!
                            __('monthlyPRpageLang.client') !!}</th>
                        <th><input type="hidden" id="title6" name="title6" value="機種">{!!
                            __('monthlyPRpageLang.machine') !!}</th>
                        <th><input type="hidden" id="title7" name="title7" value="製程">{!!
                            __('monthlyPRpageLang.process') !!}</th>
                        <th><input type="hidden" id="title8" name="title8" value="當月站位人數">{!!
                            __('monthlyPRpageLang.nowpeople') !!}</th>
                        <th><input type="hidden" id="title9" name="title9" value="當月開線數">{!!
                            __('monthlyPRpageLang.nowline') !!}</th>
                        <th><input type="hidden" id="title10" name="title10" value="當月開班數">{!!
                            __('monthlyPRpageLang.nowclass') !!}</th>
                        <th><input type="hidden" id="title11" name="title11" value="當月每人每日需求量">{!!
                            __('monthlyPRpageLang.nowuse') !!}</th>
                        <th><input type="hidden" id="title12" name="title12" value="當月每日更換頻率">{!!
                            __('monthlyPRpageLang.nowchange') !!}</th>
                        <th><input type="hidden" id="title13" name="title13" value="當月每日需求">{!!
                            __('monthlyPRpageLang.nowdayneed') !!}</th>
                        <th><input type="hidden" id="title14" name="title14" value="下月站位人數">{!!
                            __('monthlyPRpageLang.nextpeople') !!}</th>
                        <th><input type="hidden" id="title15" name="title15" value="下月開線數">{!!
                            __('monthlyPRpageLang.nextline') !!}</th>
                        <th><input type="hidden" id="title16" name="title16" value="下月開班數">{!!
                            __('monthlyPRpageLang.nextclass') !!}</th>
                        <th><input type="hidden" id="title17" name="title17" value="下月每人每日需求量">{!!
                            __('monthlyPRpageLang.nextuse') !!}</th>
                        <th><input type="hidden" id="title18" name="title18" value="下月每日更換頻率">{!!
                            __('monthlyPRpageLang.nextchange') !!}</th>
                        <th><input type="hidden" id="title19" name="title19" value="下月每日需求">{!!
                            __('monthlyPRpageLang.nextdayneed') !!}</th>
                        <th><input type="hidden" id="title20" name="title20" value="安全庫存">{!!
                            __('monthlyPRpageLang.safe') !!}</th>
                        <th><input type="hidden" id="title21" name="title21" value="備註">{!!
                            __('monthlyPRpageLang.remark') !!}</th>
                        <input type="hidden" id="title" name="title" value="22">
                    </tr>
                    @foreach($data as $data)
                    <?php $test = str_replace(";","<br>",$data->紀錄) ;?>
                    <tr>
                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                style="width:20px;height:20px;" value="{{$loop->index}}"></td>
                        <td><input type="hidden" id="data0{{$loop->index}}" name="data0{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="data1{{$loop->index}}" name="data1{{$loop->index}}"
                                value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="data2{{$loop->index}}" name="data2{{$loop->index}}"
                                value="{{$data->單位}}">{{$data->單位}}</td>
                        <td><input type="hidden" id="data3{{$loop->index}}" name="data3{{$loop->index}}"
                                value="{{$data->MPQ}}">{{$data->MPQ}}</td>
                        <td><input type="hidden" id="data4{{$loop->index}}" name="data4{{$loop->index}}"
                                value="{{$data->LT}}">{{$data->LT}}</td>
                        <td><input type="hidden" id="data5{{$loop->index}}" name="data5{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="data6{{$loop->index}}" name="data6{{$loop->index}}"
                                value="{{$data->機種}}">{{$data->機種}}</td>
                        <td><input type="hidden" id="data7{{$loop->index}}" name="data7{{$loop->index}}"
                                value="{{$data->製程}}">{{$data->製程}}</td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data8{{$loop->index}}" name="data8{{$loop->index}}" value="{{$data->當月站位人數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)"></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data9{{$loop->index}}" name="data9{{$loop->index}}" value="{{$data->當月開線數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)"></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data10{{$loop->index}}" name="data10{{$loop->index}}" value="{{$data->當月開班數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)"></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data11{{$loop->index}}" name="data11{{$loop->index}}" value="{{$data->當月每人每日需求量}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)">
                        </td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data12{{$loop->index}}" name="data12{{$loop->index}}" value="{{$data->當月每日更換頻率}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)">
                        </td>
                        <td><input style="width:100px" class="form-control form-control-lg" type="number"
                                id="data13{{$loop->index}}" name="data13{{$loop->index}}" readonly></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data14{{$loop->index}}" name="data14{{$loop->index}}" value="{{$data->下月站位人數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)">
                        </td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data15{{$loop->index}}" name="data15{{$loop->index}}" value="{{$data->下月開線數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)"></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data16{{$loop->index}}" name="data16{{$loop->index}}" value="{{$data->下月開班數}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)"></td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data17{{$loop->index}}" name="data17{{$loop->index}}" value="{{$data->下月每人每日需求量}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)">
                        </td>
                        <td><input style="width:150px" class="form-control form-control-lg" type="number"
                                id="data18{{$loop->index}}" name="data18{{$loop->index}}" value="{{$data->下月每日更換頻率}}" step="0.0000001"
                                oninput="if(value.length>9)value=value.slice(0,9)">
                        </td>
                        <td><input style="width:100px" class="form-control form-control-lg" type="number"
                                id="data19{{$loop->index}}" name="data19{{$loop->index}}" readonly></td>
                        <td><input style="width:100px" class="form-control form-control-lg" type="number"
                                id="data20{{$loop->index}}" name="data20{{$loop->index}}" readonly></td>
                        <td id="data21{{$loop->index}}">
                            <?php
                                        echo $test;
                                    ?>
                        </td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach

                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
            <input type="text" id="jobnumber" name="jobnumber">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
            <input type="email" id="email" name="email" pattern=".+@pegatroncorp\.com"
                placeholder="xxx@pegatroncorp.com">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.delete') !!}">
            <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.change') !!}">
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.download') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.stand')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
