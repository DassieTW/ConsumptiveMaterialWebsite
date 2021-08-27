@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/uploadstand.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.standAdd') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadstand') }}">
                            @csrf
                            <div class="col-6 col-sm-3">
                                <label>{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('month.insertuploadstand') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                    <th><input type = "hidden" id = "title3" name = "title3" value = "MPQ">{!! __('monthlyPRpageLang.mpq') !!}</th>
                                    <th><input type = "hidden" id = "title4" name = "title4" value = "LT">{!! __('monthlyPRpageLang.lt') !!}</th>
                                    <th><input type = "hidden" id = "title5" name = "title5" value = "當月站位人數">{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                    <th><input type = "hidden" id = "title6" name = "title6" value = "當月開線數">{!! __('monthlyPRpageLang.nowline') !!}</th>
                                    <th><input type = "hidden" id = "title7" name = "title7" value = "當月開班數">{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                    <th><input type = "hidden" id = "title8" name = "title8" value = "當月每人每日需求量">{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                    <th><input type = "hidden" id = "title9" name = "title9" value = "當月每日更換頻率">{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                    <th><input type = "hidden" id = "title10" name = "title10" value = "當月每日需求">{!! __('monthlyPRpageLang.nowdayneed') !!}</th>
                                    <th><input type = "hidden" id = "title11" name = "title11" value = "下月站位人數">{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                    <th><input type = "hidden" id = "title12" name = "title12" value = "下月開線數">{!! __('monthlyPRpageLang.nextline') !!}</th>
                                    <th><input type = "hidden" id = "title13" name = "title13" value = "下月開班數">{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                    <th><input type = "hidden" id = "title14" name = "title14" value = "下月每人每日需求量">{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                    <th><input type = "hidden" id = "title15" name = "title15" value = "下月每日更換頻率">{!! __('monthlyPRpageLang.nextchange') !!}</th>
                                    <th><input type = "hidden" id = "title16" name = "title16" value = "下月每日需求">{!! __('monthlyPRpageLang.nextdayneed') !!}</th>
                                    <th><input type = "hidden" id = "title17" name = "title17" value = "安全庫存">{!! __('monthlyPRpageLang.safestock') !!}</th>
                                    <th><input type = "hidden" id = "title18" name = "title18" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title19" name = "title19" value = "機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th><input type = "hidden" id = "title20" name = "title20" value = "製程">{!! __('monthlyPRpageLang.process') !!}</th>

                                    <input type = "hidden" id = "time" name = "time" value = "21">
                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <?php
                                            $name = DB::table('consumptive_material')->where('料號',$row[0])->value('品名');
                                            $unit = DB::table('consumptive_material')->where('料號',$row[0])->value('單位');
                                            $mpq = DB::table('consumptive_material')->where('料號',$row[0])->value('MPQ');
                                            $lt = DB::table('consumptive_material')->where('料號',$row[0])->value('LT');
                                            $month = DB::table('consumptive_material')->where('料號',$row[0])->value('月請購');
                                            $belong = DB::table('consumptive_material')->where('料號',$row[0])->value('耗材歸屬');
                                            $clients = DB::table('客戶別')->pluck('客戶')->toArray();
                                            $machines = DB::table('機種')->pluck('機種')->toArray();
                                            $productions = DB::table('製程')->pluck('製程')->toArray();
                                            $i = false;
                                            $j = false;
                                            $k = false;

                                            //判斷是否有料號
                                            if($name === null || $unit === null || $month === '否' || $belong !== '站位')
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                window.alert('Material is not found , Please check Material number');
                                                window.location.href = 'uploadstand';
                                                </script>");
                                            }
                                            //判斷是否有這個客戶
                                            if(in_array($row[1],$clients)) $i = true;

                                            if($i === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[1] ' + 'in 客戶別');
                                                    window.location.href = 'uploadstand';
                                                    </script>");
                                            }

                                            //判斷是否有這個機種
                                            if(in_array($row[2],$machines)) $j = true;

                                            if($j === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[2] ' + 'in 機種');
                                                    window.location.href = 'uploadstand';
                                                    </script>");
                                            }

                                            //判斷是否有這個製程
                                            if(in_array($row[3],$productions)) $k = true;

                                            if($k === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[3] ' + 'in 製程');
                                                    window.location.href = 'uploadstand';
                                                    </script>");
                                            }
                                        ?>
                                        <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$row[0]}}">{{$row[0]}}</td>
                                        <td>{{$name}}</td>
                                        <td>{{$unit}}</td>
                                        <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$mpq}}">{{$mpq}}</td>
                                        <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$lt}}">{{$lt}}</td>
                                        <td><input style="width:50px" type = "number" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$row[4]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$row[5]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$row[6]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$row[7]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$row[8]}}" ></td>
                                        <td><input style="width:70px" type = "number" id = "data8{{$loop->index}}" name = "data8{{$loop->index}}" readonly></td>
                                        <td><input style="width:50px" type = "number" id = "data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$row[9]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "{{$row[10]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$row[11]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "{{$row[12]}}" ></td>
                                        <td><input style="width:50px" type = "number" id = "data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "{{$row[13]}}" ></td>
                                        <td><input style="width:70px" type = "number" id = "data14{{$loop->index}}" name = "data14{{$loop->index}}" readonly></td>
                                        <td><input style="width:70px" type = "number" id = "data15{{$loop->index}}" name = "data15{{$loop->index}}" readonly></td>
                                        <td><input type = "hidden" id = "data16{{$loop->index}}" name = "data16{{$loop->index}}"  value = "{{$row[1]}}">{{$row[1]}}</td>
                                        <td><input type = "hidden" id = "data17{{$loop->index}}" name = "data17{{$loop->index}}"  value = "{{$row[2]}}">{{$row[2]}}</td>
                                        <td><input type = "hidden" id = "data18{{$loop->index}}" name = "data18{{$loop->index}}"  value = "{{$row[3]}}">{{$row[3]}}</td>

                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.standadd')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
