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

    form input {
        position: sticky;
        top: 0;
        z-index: 10;
    }

</style>
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/searchstock.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.inbound') !!}</h2>
<div class="card">
    <form id="inboundsearch" method="POST">
        @csrf
        <div class="card-header">
            <h3>{!! __('inboundpageLang.searchstock') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                value="{!! __('inboundpageLang.download') !!}">

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <input type="hidden" id="titlename" name="titlename" value="庫存">
                <table class="table ">
                    <thead>
                        <tr>
                            <th><input type="hidden" id="title0" name="title0" value="客戶別">{!!
                                __('inboundpageLang.client')
                                !!}</th>
                            <th><input type="hidden" id="title1" name="title1" value="料號">{!! __('inboundpageLang.isn')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title2" value="品名">{!!
                                __('inboundpageLang.pName')
                                !!}</th>
                            <th><input type="hidden" id="title3" name="title3" value="規格">{!!
                                __('inboundpageLang.format')
                                !!}</th>
                            <th><input type="hidden" id="title4" name="title4" value="單位">{!! __('inboundpageLang.unit')
                                !!}
                            </th>
                            <th><input type="hidden" id="title5" name="title5" value="單價">{!!
                                __('inboundpageLang.price')
                                !!}</th>
                            <th><input type="hidden" id="title6" name="title6" value="幣別">{!!
                                __('inboundpageLang.money')
                                !!}</th>
                            <th><input type="hidden" id="title7" name="title7" value="A級資材">{!!
                                __('inboundpageLang.gradea')
                                !!}</th>
                            <th><input type="hidden" id="title8" name="title8" value="月請購">{!!
                                __('inboundpageLang.month')
                                !!}</th>
                            <th><input type="hidden" id="title9" name="title9" value="庫存">{!!
                                __('inboundpageLang.stock')
                                !!}</th>
                            <th><input type="hidden" id="title10" name="title10" value="安全庫存">{!!
                                __('inboundpageLang.safe')
                                !!}</th>
                            <th><input type="hidden" id="title11" name="title11" value="儲位">{!!
                                __('inboundpageLang.loc')
                                !!}</th>
                            <th><input type="hidden" id="title12" name="title12" value="呆滯天數">{!!
                                __('inboundpageLang.days')
                                !!}</th>
                            <input type="hidden" id="titlecount" name="titlecount" value="13">
                        </tr>
                    </thead>
                    @foreach($data as $data)
                    <tr class="isnRows">
                        <?php
                            $maxtime = date_create(date('Y-m-d',strtotime($data->最後更新時間)));
                            $nowtime = date_create(date('Y-m-d',strtotime(\Carbon\Carbon::now())));
                            $interval = date_diff($maxtime ,$nowtime);
                            $interval = $interval->format('%R%a');
                            $interval = (int)($interval);
                            $belong = $data->耗材歸屬;
                            $lt = $data->LT;
                            $data->單價 = floatval($data->單價);

                            if($belong === '單耗')
                                {
                                    $machine = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('機種');
                                    $production = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('製程');
                                    $consume = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('單耗');
                                    $nextmps = DB::table('MPS')->where('機種',$machine)->where('客戶別',$data->客戶別)->where('製程',$production)->value('下月MPS');
                                    $nextday = DB::table('MPS')->where('機種',$machine)->where('客戶別',$data->客戶別)->where('製程',$production)->value('下月生產天數');

                                    if($nextday == 0)
                                    {
                                        $safe = 0;
                                    }
                                    else
                                    {
                                        $safe = $lt * $consume * $nextmps / $nextday;
                                    }
                                }
                                else
                                {
                                    $machine = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('機種');
                                    $production = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('製程');
                                    $nextstand = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('下月站位人數');
                                    $nextline = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('下月開線數');
                                    $nextclass = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('下月開班數');
                                    $nextuse = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('下月每人每日需求量');
                                    $nextchange = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('下月每日更換頻率');
                                    $mpq = $data->MPQ;
                                    if($mpq == 0)
                                    {
                                        $safe = 0;
                                    }
                                    else
                                    {
                                        $safe = $lt * $nextstand * $nextline * $nextclass * $nextuse * $nextchange / $mpq;
                                    }

                                }
                        ?>
                        <td><input type="hidden" id="dataa{{$loop->index}}" name="data0{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="datab{{$loop->index}}" name="data1{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="datac{{$loop->index}}" name="data2{{$loop->index}}"
                                value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="datad{{$loop->index}}" name="data3{{$loop->index}}"
                                value="{{$data->規格}}">{{$data->規格}}</td>
                        <td><input type="hidden" id="datae{{$loop->index}}" name="data4{{$loop->index}}"
                                value="{{$data->單位}}">{{$data->單位}}</td>
                        <td><input type="hidden" id="dataf{{$loop->index}}" name="data5{{$loop->index}}"
                                value="{{$data->單價}}">{{$data->單價}}</td>
                        <td><input type="hidden" id="datag{{$loop->index}}" name="data6{{$loop->index}}"
                                value="{{$data->幣別}}">{{$data->幣別}}</td>
                        <td><input type="hidden" id="datah{{$loop->index}}" name="data7{{$loop->index}}"
                                value="{{$data->A級資材}}">{{$data->A級資材}}</td>
                        <td><input type="hidden" id="datai{{$loop->index}}" name="data8{{$loop->index}}"
                                value="{{$data->月請購}}">{{$data->月請購}}</td>
                        <td><input type="hidden" id="dataj{{$loop->index}}" name="data9{{$loop->index}}"
                                value={{round($data->現有庫存 , 0)}}>{{round($data->現有庫存 , 0)}}</td>
                        <td><input type="hidden" id="datak{{$loop->index}}" name="data10{{$loop->index}}"
                                value="{{$safe}}">{{$safe}}</td>
                        <td><input type="hidden" id="datal{{$loop->index}}" name="data11{{$loop->index}}"
                                value="{{$data->儲位}}">{{$data->儲位}}</td>
                        <td><input type="hidden" id="datam{{$loop->index}}" name="data12{{$loop->index}}"
                                value="{{$interval}}">{{$interval}}</td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach

                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->


    </form>

</div>
</div>
</div>

</html>
@endsection
