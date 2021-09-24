@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('/js/bu/sluggish.js') }}"></script>
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('bupagelang.bu') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('bupagelang.sluggish') !!}</h3>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                            <form  id = "sluggish" method="POST">
                                @csrf
                                <input type = "hidden" id = "title" name = "title" value = "廠區呆滯庫存">
                            <table class="table table-bordered" id = "test">
                                <tr>
                                    <th>{!! __('bupagelang.check') !!}</th>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "廠區">{!! __('bupagelang.factory') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('bupagelang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "品名">{!! __('bupagelang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title3" name = "title3" value = "規格">{!! __('bupagelang.format') !!}</th>
                                    <th><input type = "hidden" id = "title4" name = "title4" value = "單位">{!! __('bupagelang.unit') !!}</th>
                                    <th><input type = "hidden" id = "title5" name = "title5" value = "呆滯天數">{!! __('bupagelang.days') !!}</th>
                                    <th><input type = "hidden" id = "title6" name = "title6" value = "庫存">{!! __('bupagelang.stock') !!}</th>
                                    <th><input type = "hidden" id = "title7" name = "title7" value = "撥出數量">{!! __('bupagelang.transamount') !!}</th>
                                    <th><input type = "hidden" id = "title8" name = "title8" value = "近期請購紀錄">{!! __('bupagelang.buyrecord') !!}</th>
                                    <th><input type = "hidden" id = "title9" name = "title9" value = "接收廠區">{!! __('bupagelang.receivefac') !!}</th>
                                </tr>

                                <?php $i = 0 ; $data = ''; $count=array(0,0,0,0,0); $record = array(array());?>
                                @for($i = 0 ; $i < 5 ; $i++)
                                    @foreach($test[$i] as $data)
                                        <?php
                                            $maxtime = date_create(date('Y-m-d',strtotime($data->inventory最後更新時間)));
                                            $nowtime = date_create(date('Y-m-d',strtotime(\Carbon\Carbon::now())));
                                            $interval = date_diff($maxtime ,$nowtime);
                                            $interval = $interval->format('%R%a');
                                            $stayday = (int)($interval);
                                            $buytime = array();
                                            $buytime1 = array();
                                            $buytimeco = array();
                                            $buytimeco1 = array();
                                            $database = ['default','testing','bb1','bb4','m1'];
                                            foreach ($database as $key => $value) {
                                                if($value != $database[$i])
                                                {
                                                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $value);
                                                    \DB::purge(env("DB_CONNECTION"));
                                                    $buytime[$key][0] = $value;
                                                    $buytime[$key][1] = DB::table('consumptive_material')->where('料號', $data->料號)->value('發料部門');

                                                    $buytime1[$key][0] = $value;
                                                    $buytime1[$key][1] = DB::table('consumptive_material')->where('料號', $data->料號)->value('發料部門');

                                                    $buytime[$key][2] = DB::table('請購單')->where('料號', $data->料號)->max('請購時間');
                                                    $buytime1[$key][2] = DB::table('非月請購')->where('料號', $data->料號)->max('上傳時間');

                                                    $buytimeco[$key][0] = $value;
                                                    $buytimeco[$key][1] = DB::table('consumptive_material')->where('料號', $data->料號)->value('發料部門');

                                                    $buytimeco1[$key][0] = $value;
                                                    $buytimeco1[$key][1] = DB::table('consumptive_material')->where('料號', $data->料號)->value('發料部門');

                                                    $buytimeco[$key][2] = DB::table('請購單')->where('料號', $data->料號)->max('請購時間');
                                                    $buytimeco1[$key][2] = DB::table('非月請購')->where('料號', $data->料號)->max('上傳時間');
                                                }
                                            }
                                        ?>

                                        @if($stayday > 30 && $data->inventory現有庫存 > 0)
                                        <?php $count[$i] ++;



                                        ?>
                                        <tr>
                                            <td><input class ="basic" type="checkbox" id="check{{$i}}{{$loop->index}}" name="check{{$i}}{{$loop->index}}" style="width:20px;height:20px;" value="{{$i}}{{$loop->index}}"></td>
                                            <td><input type = "hidden"  id = "data0{{$i}}{{$loop->index}}" name = "data0{{$i}}{{$loop->index}}" value = {{$database[$i]}}>{{$database[$i]}}</td>
                                            <td><input type = "hidden"  id = "data1{{$i}}{{$loop->index}}" name = "data1{{$i}}{{$loop->index}}" value = {{$data->料號}}>{{$data->料號}}</td>
                                            <td><input type = "hidden"  id = "data2{{$i}}{{$loop->index}}" name = "data2{{$i}}{{$loop->index}}" value = {{$data->品名}}>{{$data->品名}}</td>
                                            <td><input type = "hidden"  id = "data3{{$i}}{{$loop->index}}" name = "data3{{$i}}{{$loop->index}}" value = {{$data->規格}}>{{$data->規格}}</td>
                                            <td><input type = "hidden"  id = "data4{{$i}}{{$loop->index}}" name = "data4{{$i}}{{$loop->index}}" value = {{$data->單位}}>{{$data->單位}}</td>
                                            <td><input type = "hidden"  id = "data5{{$i}}{{$loop->index}}" name = "data5{{$i}}{{$loop->index}}" value = {{$stayday}}>{{$stayday}}</td>
                                            <td><input type = "hidden"  id = "data6{{$i}}{{$loop->index}}" name = "data6{{$i}}{{$loop->index}}" value = {{$data->inventory現有庫存}}>{{$data->inventory現有庫存}}</td>
                                            <td><input type = "number" id = "data7{{$i}}{{$loop->index}}" name = "data7{{$i}}{{$loop->index}}" value = "" style="width:100px;"></td>
                                            <td id = "data8{{$i}}{{$loop->index}}" name = "data8{{$i}}{{$loop->index}}">@foreach ($buytime as $buytime)
                                                @if( $buytime[2] != null)
                                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime[0]}} {!! __('bupagelang.senddep') !!} : {{$buytime[1]}} {!! __('bupagelang.buytime') !!} : {{$buytime[2]}}</span><br>
                                                @endif
                                                @endforeach
                                                @foreach ($buytime1 as $buytime1)
                                                @if( $buytime1[2] != null)
                                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime1[0]}} {!! __('bupagelang.senddep') !!} : {{$buytime1[1]}} {!! __('bupagelang.buytime') !!} : {{$buytime1[2]}}</span><br>
                                                @endif
                                                @endforeach</td>
                                            <td>
                                                <select class="form-control form-control-lg" id = "data9{{$i}}{{$loop->index}}" name="data9{{$i}}{{$loop->index}}" style = "width: 200px">
                                                <option style="display: none" disabled selected>{!! __('bupagelang.enterfactory') !!}</option>
                                                @foreach($buytimeco as $buytime)
                                                @if( $buytime[2] != null)
                                                <option>{{  $buytime[0] }}</option>
                                                @endif
                                                @endforeach
                                                @foreach($buytimeco1 as $buytime1)
                                                @if ($buytime1[2] != null)
                                                <option>{{  $buytime1[0] }}</option>
                                                @endif
                                                @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    <input type = "hidden" id = "count{{$i}}" name = "count{{$i}}" value = "{{$count[$i]}}" ></td>
                                @endfor

                            </table>
                            </div>
                            <br>
                            <input type = "submit" id = "submit"  name = "submit" class="btn btn-lg btn-primary" value="{!! __('bupagelang.submit') !!}">
                            <input type = "submit" id = "download"  name = "download" class="btn btn-lg btn-primary" value="{!! __('bupagelang.download') !!}">

                        </form>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.index')}}'">{!! __('bupagelang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
