@foreach($data as $row)
<?php
    $name = DB::table('consumptive_material')->where('料號',$row[3])->value('品名');
    $format = DB::table('consumptive_material')->where('料號',$row[3])->value('規格');
    $unit = DB::table('consumptive_material')->where('料號',$row[3])->value('單位');
    $lt = DB::table('consumptive_material')->where('料號',$row[3])->value('LT');
    $month = DB::table('consumptive_material')->where('料號',$row[3])->value('月請購');
    $belong = DB::table('consumptive_material')->where('料號',$row[3])->value('耗材歸屬');
    $clients = DB::table('客戶別')->pluck('客戶')->toArray();
    $machines = DB::table('機種')->pluck('機種')->toArray();
    $productions = DB::table('製程')->pluck('制程')->toArray();
    $i = false;
    $j = false;
    $k = false;
    $error = $loop->index +1;
    //判斷是否有料號
    if($name === null || $format === null || $month ==='否' || $belong !=='單耗')
    {
        $mess = trans('monthlyPRpageLang.noisn').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[3];
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadconsume';
            </script>");
    }
    //判斷是否有這個客戶
    if(in_array($row[0],$clients)) $i = true;

    if($i === false)
    {
        $mess = trans('monthlyPRpageLang.noclient').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[0];
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadconsume';
            </script>");
    }

    //判斷是否有這個機種
    if(in_array($row[1],$machines)) $j = true;

    if($j === false)
    {
        $mess = trans('monthlyPRpageLang.nomachine').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[1];
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadconsume';
            </script>");
    }

    //判斷是否有這個製程
    if(in_array($row[2],$productions)) $k = true;

    if($k === false)
    {
        $mess = trans('monthlyPRpageLang.noproduction').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[2];
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadconsume';
            </script>");
    }
?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/uploadconsume.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.monthly') !!}</h2>
<div id="url"></div>
<div id="consumebody">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="card w-100">
                <div class="card-header">
                    <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
                </div>
                <form id="uploadconsume" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><input type="hidden" id="title0" name="title0" value="料號">{!!
                                    __('monthlyPRpageLang.isn') !!}</th>
                                <th><input type="hidden" id="title1" name="title1" value="品名">{!!
                                    __('monthlyPRpageLang.pName') !!}</th>
                                <th><input type="hidden" id="title2" name="title2" value="規格">{!!
                                    __('monthlyPRpageLang.format') !!}</th>
                                <th><input type="hidden" id="title3" name="title3" value="單位">{!!
                                    __('monthlyPRpageLang.unit') !!}</th>
                                <th><input type="hidden" id="title4" name="title4" value="LT">{!!
                                    __('monthlyPRpageLang.lt') !!}</th>
                                <th><input type="hidden" id="title5" name="title5" value="單耗">{!!
                                    __('monthlyPRpageLang.consume') !!}</th>
                                <th><input type="hidden" id="title6" name="title6" value="當月每日需求">{!!
                                    __('monthlyPRpageLang.nowdayneed') !!}</th>
                                <th><input type="hidden" id="title7" name="title7" value="下月每日需求">{!!
                                    __('monthlyPRpageLang.nextdayneed') !!}</th>
                                <th><input type="hidden" id="title8" name="title8" value="安全庫存">{!!
                                    __('monthlyPRpageLang.safe') !!}</th>
                                <th><input type="hidden" id="title9" name="title9" value="客戶別">{!!
                                    __('monthlyPRpageLang.client') !!}</th>
                                <th><input type="hidden" id="title10" name="title10" value="機種">{!!
                                    __('monthlyPRpageLang.machine') !!}</th>
                                <th><input type="hidden" id="title11" name="title11" value="製程">{!!
                                    __('monthlyPRpageLang.process') !!}</th>
                                <th><input type="hidden" id="title12" name="title12" value="當月MPS">{!!
                                    __('monthlyPRpageLang.nowmps') !!}</th>
                                <th><input type="hidden" id="title13" name="title13" value="當月生產天數">{!!
                                    __('monthlyPRpageLang.nowday') !!}</th>
                                <th><input type="hidden" id="title14" name="title14" value="下月MPS">{!!
                                    __('monthlyPRpageLang.nextmps') !!}</th>
                                <th><input type="hidden" id="title15" name="title15" value="下月生產天數">{!!
                                    __('monthlyPRpageLang.nextday') !!}</th>
                            </tr>
                            @foreach($data as $row)
                            <tr>
                                <?php
                                    $name = DB::table('consumptive_material')->where('料號',$row[3])->value('品名');
                                    $format = DB::table('consumptive_material')->where('料號',$row[3])->value('規格');
                                    $unit = DB::table('consumptive_material')->where('料號',$row[3])->value('單位');
                                    $lt = DB::table('consumptive_material')->where('料號',$row[3])->value('LT');
                                    $month = DB::table('consumptive_material')->where('料號',$row[3])->value('月請購');
                                    $belong = DB::table('consumptive_material')->where('料號',$row[3])->value('耗材歸屬');
                                    $lt = round($lt, 3);
                                ?>
                                <td><input type="hidden" id="data0{{$loop->index}}" name="data0{{$loop->index}}"
                                        value="{{$row[3]}}">{{$row[3]}}</td>
                                <td>{{$name}}</td>
                                <td>{{$format}}</td>
                                <td>{{$unit}}</td>
                                <td><input type="hidden" id="data12{{$loop->index}}" name="data12{{$loop->index}}"
                                        value="{{$lt}}">{{$lt}}</td>
                                <td><input style="width:200px" type="number" id="data1{{$loop->index}}"
                                        name="data1{{$loop->index}}" step="0.0000000001" required value="{{$row[4]}}"
                                        oninput="if(value.length>12)value=value.slice(0,12)"></td>
                                <td><input style="width:120px" type="number" id="data2{{$loop->index}}"
                                        name="data2{{$loop->index}}" readonly step="0.0000001" >
                                </td>
                                <td><input style="width:120px" type="number" id="data3{{$loop->index}}"
                                        name="data3{{$loop->index}}" readonly step="0.0000001">
                                </td>
                                <td><input style="width:120px" type="number" id="data4{{$loop->index}}"
                                        name="data4{{$loop->index}}" readonly step="0.0000001">
                                </td>
                                <td><input type="hidden" id="data5{{$loop->index}}" name="data5{{$loop->index}}"
                                        value="{{$row[0]}}">{{$row[0]}}</td>
                                <td><input type="hidden" id="data6{{$loop->index}}" name="data6{{$loop->index}}"
                                        value="{{$row[1]}}">{{$row[1]}}</td>
                                <td><input type="hidden" id="data7{{$loop->index}}" name="data7{{$loop->index}}"
                                        value="{{$row[2]}}">{{$row[2]}}</td>
                                <td><input style="width:80px" type="number" id="data8{{$loop->index}}"
                                        name="data8{{$loop->index}}" step="0.01" oninput="if(value.length>4)value=value.slice(0,4)"></td>
                                <td><input style="width:80px" type="number" id="data9{{$loop->index}}"
                                        name="data9{{$loop->index}}" step="0.01" oninput="if(value.length>4)value=value.slice(0,4)"></td>
                                <td><input style="width:80px" type="number" id="data10{{$loop->index}}"
                                        name="data10{{$loop->index}}" step="0.01" oninput="if(value.length>4)value=value.slice(0,4)"></td>
                                <td><input style="width:80px" type="number" id="data11{{$loop->index}}"
                                        name="data11{{$loop->index}}" step="0.01" oninput="if(value.length>4)value=value.slice(0,4)"></td>

                            </tr>
                            <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                            @endforeach

                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
                    <input type="text" id="jobnumber" name="jobnumber" required>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                    <input type="email" id="email" name="email" pattern=".+@pegatroncorp\.com" required
                        placeholder="xxx@pegatroncorp.com">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <input type="submit" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <div class="row w-100 justify-content-start">
                    <div class="col col-auto">
                        <button class="btn btn-lg btn-primary"
                            onclick="location.href='{{route('month.consumeadd')}}'">{!!
                            __('monthlyPRpageLang.return') !!}</button>
                    </div>
                </div>
            </div>
        </div>

</html>
@endsection
