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
            <form id="sluggish" method="POST">
                @csrf
                <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
            value="{!! __('bupagelang.submit') !!}">
        <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
            value="{!! __('bupagelang.download') !!}">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <input type="hidden" id="title" name="title" value="廠區呆滯庫存">
                <table class="table table-bordered" id="test">
                    <tr>
                        <th>{!! __('bupagelang.check') !!}</th>
                        <th><input type="hidden" id="title0" name="title0" value="廠區">{!! __('bupagelang.factory') !!}
                        </th>
                        <th><input type="hidden" id="title1" name="title1" value="料號">{!! __('bupagelang.isn') !!}</th>
                        <th><input type="hidden" id="title2" name="title2" value="品名">{!! __('bupagelang.pName') !!}
                        </th>
                        <th><input type="hidden" id="title3" name="title3" value="規格">{!! __('bupagelang.format') !!}
                        </th>
                        <th><input type="hidden" id="title4" name="title4" value="單位">{!! __('bupagelang.unit') !!}</th>
                        <th><input type="hidden" id="title5" name="title5" value="呆滯天數">{!! __('bupagelang.days') !!}
                        </th>
                        <th><input type="hidden" id="title6" name="title6" value="庫存">{!! __('bupagelang.stock') !!}
                        </th>
                        <th><input type="hidden" id="title7" name="title7" value="撥出數量">{!! __('bupagelang.transamount')
                            !!}</th>
                        <th><input type="hidden" id="title8" name="title8" value="近期請購紀錄">{!! __('bupagelang.buyrecord')
                            !!}</th>
                        <th><input type="hidden" id="title9" name="title9" value="接收廠區">{!! __('bupagelang.receivefac')
                            !!}</th>
                    </tr>

                    <?php $i = 0 ; $data = ''; $record = array(array());

                    ?>
                    @for($i = 0 ; $i < 6 ; $i++) @foreach($test[$i] as $data) <?php
                        $maxtime=date_create(date('Y-m-d',strtotime($data->inventory最後更新時間)));
                        $nowtime = date_create(date('Y-m-d',strtotime(\Carbon\Carbon::now())));
                        $interval = date_diff($maxtime ,$nowtime);
                        $interval = $interval->format('%R%a');
                        $stayday = (int)($interval);
                        $buytime = array();
                        $buytime1 = array();
                        $buytimeco = array();
                        $buytimeco1 = array();
                        $database = ['M2_TEST_1112','巴淡SMT1214','BB1_1214 Consumables management' ,
                        '巴淡-LOT11 Consumables management' , '巴淡-LOT2 Consumables management' , '巴淡-PTSN Consumables management'];
                        foreach ($database as $key => $value) {
                        if($value != $database[$i]){
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
                        @if(isset($table))
                        @if($database[$i] === $table)
                        <tr>
                            <td><input class="basic" type="checkbox" id="check{{$i}}{{$loop->index}}"
                                    name="check{{$i}}{{$loop->index}}" style="width:20px;height:20px;"
                                    value="{{$i}}{{$loop->index}}"></td>
                            <td><input type="hidden" id="dataa{{$i}}{{$loop->index}}" name="dataa{{$i}}{{$loop->index}}"
                                    value="{{$database[$i]}}">{{$database[$i]}}</td>
                            <td><input type="hidden" id="datab{{$i}}{{$loop->index}}" name="datab{{$i}}{{$loop->index}}"
                                    value={{$data->料號}}>{{$data->料號}}</td>
                            <td><input type="hidden" id="datac{{$i}}{{$loop->index}}" name="datac{{$i}}{{$loop->index}}"
                                    value={{$data->品名}}>{{$data->品名}}</td>
                            <td><input type="hidden" id="datad{{$i}}{{$loop->index}}" name="datad{{$i}}{{$loop->index}}"
                                    value={{$data->規格}}>{{$data->規格}}</td>
                            <td><input type="hidden" id="datae{{$i}}{{$loop->index}}" name="datae{{$i}}{{$loop->index}}"
                                    value={{$data->單位}}>{{$data->單位}}</td>
                            <td><input type="hidden" id="dataf{{$i}}{{$loop->index}}" name="dataf{{$i}}{{$loop->index}}"
                                    value={{$stayday}}>{{$stayday}}</td>
                            <td><input type="hidden" id="datag{{$i}}{{$loop->index}}" name="datag{{$i}}{{$loop->index}}"
                                    value={{$data->inventory現有庫存}}>{{$data->inventory現有庫存}}</td>
                            <td><input type="number" id="datah{{$i}}{{$loop->index}}" name="data7{{$i}}{{$loop->index}}"
                                    value="1" style="width:100px;" min="0"></td>
                            <td id="datai{{$i}}{{$loop->index}}" name="datai{{$i}}{{$loop->index}}">@foreach ($buytime
                                as $buytime)
                                @if( $buytime[2] != null)
                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime[0]}}
                                    {!! __('bupagelang.senddep') !!} : {{$buytime[1]}} {!! __('bupagelang.buytime') !!}
                                    : {{$buytime[2]}}</span><br>
                                @endif
                                @endforeach
                                @foreach ($buytime1 as $buytime1)
                                @if( $buytime1[2] != null)
                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime1[0]}}
                                    {!! __('bupagelang.senddep') !!} : {{$buytime1[1]}} {!! __('bupagelang.buytime') !!}
                                    : {{$buytime1[2]}}</span><br>
                                @endif
                                @endforeach
                            </td>
                            <td>
                                <select class="form-select form-select-lg" id="dataj{{$i}}{{$loop->index}}"
                                    name="dataj{{$i}}{{$loop->index}}" style="width: 200px">
                                    <option style="display: none" disabled selected>{!! __('bupagelang.enterfactory')
                                        !!}</option>
                                    @foreach($buytimeco as $buytime)
                                    <option>{{ $buytime[0] }}</option>
                                    @endforeach

                            </td>
                        </tr>
                        @endif
                        @else
                        <tr>
                            <td><input class="basic" type="checkbox" id="check{{$i}}{{$loop->index}}"
                                    name="check{{$i}}{{$loop->index}}" style="width:20px;height:20px;"
                                    value="{{$i}}{{$loop->index}}"></td>
                            <td><input type="hidden" id="dataa{{$i}}{{$loop->index}}" name="dataa{{$i}}{{$loop->index}}"
                                    value={{$database[$i]}}>{{$database[$i]}}</td>
                            <td><input type="hidden" id="datab{{$i}}{{$loop->index}}" name="datab{{$i}}{{$loop->index}}"
                                    value={{$data->料號}}>{{$data->料號}}</td>
                            <td><input type="hidden" id="datac{{$i}}{{$loop->index}}" name="datac{{$i}}{{$loop->index}}"
                                    value={{$data->品名}}>{{$data->品名}}</td>
                            <td><input type="hidden" id="datad{{$i}}{{$loop->index}}" name="datad{{$i}}{{$loop->index}}"
                                    value={{$data->規格}}>{{$data->規格}}</td>
                            <td><input type="hidden" id="datae{{$i}}{{$loop->index}}" name="datae{{$i}}{{$loop->index}}"
                                    value={{$data->單位}}>{{$data->單位}}</td>
                            <td><input type="hidden" id="dataf{{$i}}{{$loop->index}}" name="dataf{{$i}}{{$loop->index}}"
                                    value={{$stayday}}>{{$stayday}}</td>
                            <td><input type="hidden" id="datag{{$i}}{{$loop->index}}" name="datag{{$i}}{{$loop->index}}"
                                    value={{$data->inventory現有庫存}}>{{$data->inventory現有庫存}}</td>
                            <td><input type="number" class="form-control form-control-lg"
                                    id="datah{{$i}}{{$loop->index}}" name="datah{{$i}}{{$loop->index}}" value="1"
                                    style="width:100px;" min="0"></td>
                            <td id="datai{{$i}}{{$loop->index}}" name="datai{{$i}}{{$loop->index}}">@foreach ($buytime
                                as $buytime)
                                @if( $buytime[2] != null)
                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime[0]}}
                                    {!! __('bupagelang.senddep') !!} : {{$buytime[1]}} {!! __('bupagelang.buytime') !!}
                                    : {{$buytime[2]}}</span><br>
                                @endif
                                @endforeach
                                @foreach ($buytime1 as $buytime1)
                                @if( $buytime1[2] != null)
                                <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} : {{$buytime1[0]}}
                                    {!! __('bupagelang.senddep') !!} : {{$buytime1[1]}} {!! __('bupagelang.buytime') !!}
                                    : {{$buytime1[2]}}</span><br>
                                @endif
                                @endforeach
                            </td>
                            <td>
                                <select class="form-select form-select-lg" id="dataj{{$i}}{{$loop->index}}"
                                    name="dataj{{$i}}{{$loop->index}}" style="width: 200px">
                                    <option style="display: none" disabled selected>{!! __('bupagelang.enterfactory')
                                        !!}</option>
                                    @foreach($buytimeco as $buytime)
                                    <option>{{ $buytime[0] }}</option>
                                    @endforeach

                            </td>




                        </tr>
                        @endif
                        @endforeach
                        @endfor
                </table>
        </div>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->


        </form>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.index')}}'">{!!
            __('bupagelang.return') !!}</button>
    </div>
</div>

</html>
@endsection
