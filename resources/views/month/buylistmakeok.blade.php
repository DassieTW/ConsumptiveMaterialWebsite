@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/buylist.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.PR') !!}</h3>
            </div>
            <div class="card-body">
                        <form action="{{route('month.buylistsubmit')}}" method="POST" id="buylist" enctype="multipart/form-data">
                            @csrf
                            <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td><input type = "hidden" name = "title0" value = "SRM單號">{!! __('monthlyPRpageLang.srm') !!}</td>
                                <td><input type = "hidden" name = "title1" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</td>
                                <td><input type = "hidden" name = "title2" value = "料號">{!! __('monthlyPRpageLang.isn') !!}</td>
                                <td><input type = "hidden" name = "title3" value = "品名">{!! __('monthlyPRpageLang.pName') !!}</td>
                                <td><input type = "hidden" name = "title4" value = "MOQ">{!! __('monthlyPRpageLang.moq') !!}</td>
                                <td><input type = "hidden" name = "title5" value = "下月需求">{!! __('monthlyPRpageLang.nextneed') !!}</td>
                                <td><input type = "hidden" name = "title6" value = "當月需求">{!! __('monthlyPRpageLang.nowneed') !!}</td>
                                <td><input type = "hidden" name = "title7" value = "安全庫存">{!! __('monthlyPRpageLang.safestock') !!}</td>
                                <td><input type = "hidden" name = "title8" value = "單價">{!! __('monthlyPRpageLang.price') !!}</td>
                                <td><input type = "hidden" name = "title9" value = "幣別">{!! __('monthlyPRpageLang.money') !!}</td>
                                <td><input type = "hidden" name = "title10" value = "匯率">{!! __('monthlyPRpageLang.rate') !!}</td>
                                <td><input type = "hidden" name = "title11" value = "在途數量">{!! __('monthlyPRpageLang.transit') !!}</td>
                                <td><input type = "hidden" name = "title12" value = "現有庫存">{!! __('monthlyPRpageLang.nowstock') !!}</td>
                                <td><input type = "hidden" name = "title13" value = "本次請購數量">{!! __('monthlyPRpageLang.buyamount') !!}</td>
                                <td><input type = "hidden" name = "title14" value = "實際需求">{!! __('monthlyPRpageLang.realneed') !!}</td>
                                <td><input type = "hidden" name = "title15" value = "請購金額">{!! __('monthlyPRpageLang.buyprice') !!}</td>
                                <td><input type = "hidden" name = "title16" value = "請購占比">{!! __('monthlyPRpageLang.buyper') !!}</td>
                                <td><input type = "hidden" name = "title17" value = "需求金額">{!! __('monthlyPRpageLang.needprice') !!}</td>
                                <td><input type = "hidden" name = "title18" value = "需求占比">{!! __('monthlyPRpageLang.needper') !!}</td>
                                <input type = "hidden" name = "time" value = "19">
                            </tr>
                            @foreach($data1 as $data)
                            <?php

                                $amounta = DB::table('在途量')->where('料號',$data->料號)->where('客戶',$data->客戶別)->sum('請購數量');
                                $stocka = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->sum('現有庫存');
                                $nextneeda = ($data->下月MPS ) * $data->單耗;
                                $nowneeda = ($data->本月MPS ) * $data->單耗;
                                $safea = $nextneeda * ($data->LT ) / $data->下月生產天數;
                                $realneeda = $nextneeda + $nowneeda + $safea - $stocka - $amounta;
                                $real = $realneeda < 0 ? 0:$realneeda;

                            ?>
                            <tr>
                                <td><input type="text" id="srmnumbera{{$loop->index}}" name="srmnumbera{{$loop->index}}" style="width:60px"></td>
                                <td><input type = "hidden" id = "clienta{{$loop->index}}" name = "clienta{{$loop->index}}" value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                                <td><input type = "hidden" id = "numbera{{$loop->index}}" name = "numbera{{$loop->index}}" value="{{$data->料號}}">{{$data->料號}}</td>
                                <td><input type = "hidden" id = "namea{{$loop->index}}" name = "namea{{$loop->index}}" value="{{$data->品名}}">{{$data->品名}}</td>
                                <td><input type = "hidden" id = "moqa{{$loop->index}}" name = "moqa{{$loop->index}}" value="{{$data->MOQ}}">{{$data->MOQ}}</td>
                                <td><input type = "hidden" id = "nextneeda{{$loop->index}}" name = "nextneeda{{$loop->index}}" value="{{$nextneeda}}">{{$nextneeda}}</td>
                                <td><input type = "hidden" id = "nowneeda{{$loop->index}}" name = "nowneeda{{$loop->index}}" value="{{$nowneeda}}">{{$nowneeda}}</td>
                                <td><input type = "hidden" id = "safea{{$loop->index}}" name = "safea{{$loop->index}}" value="{{$safea}}">{{$safea}}</td>
                                <td><input type = "hidden" id = "pricea{{$loop->index}}" name = "pricea{{$loop->index}}" value="{{$data->單價}}">{{$data->單價}}</td>
                                <td><input type = "hidden" id = "moneya{{$loop->index}}" name = "moneya{{$loop->index}}" value="{{$data->幣別}}">{{$data->幣別}}</td>
                                <td><input type = "hidden" id = "ratea{{$loop->index}}" name = "ratea{{$loop->index}}" value="{{$rate1[$loop->index]}}">{{$rate1[$loop->index]}}</td>
                                <td><input type = "hidden" id = "amounta{{$loop->index}}" name = "amounta{{$loop->index}}" value="{{$amounta}}">{{$amounta}}</td>
                                <td><input type = "hidden" id = "stocka{{$loop->index}}" name = "stocka{{$loop->index}}" value="{{$stocka}}">{{$stocka}}</td>
                                <td><input type = "number" id = "buyamounta{{$loop->index}}" name = "buyamounta{{$loop->index}}" required style="width:60px" value="{{$real}}"></td>
                                <td><input type = "hidden" id = "realneeda{{$loop->index}}" name = "realneeda{{$loop->index}}" value="{{$realneeda}}">{{$realneeda}}</td>
                                <td><input id = "buymoneya{{$loop->index}}" name = "buymoneya{{$loop->index}}" style="width:70px" readonly></td>
                                <td><input id = "buypera{{$loop->index}}" name = "buypera{{$loop->index}}" style="width:70px" readonly step="0.01"></td>
                                <td><input id = "needmoneya{{$loop->index}}" name = "needmoneya{{$loop->index}}" style="width:70px" readonly></td>
                                <td><input id = "needpera{{$loop->index}}" name = "needpera{{$loop->index}}"  style="width:70px" readonly step="0.01"></td>
                            </tr>
                            <input type = "hidden" id = "counta" name = "counta" value="{{$loop->count}}">
                            @endforeach
                            @foreach($data2 as $data)
                            <?php

                                $amountb = DB::table('在途量')->where('料號',$data->料號)->where('客戶',$data->客戶別)->sum('請購數量');
                                $stockb = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->sum('現有庫存');
                                $nowneedb = ($data->當月站位人數 ) * ($data->當月開線數 ) * ($data->當月開班數 ) *
                                ($data->當月每人每日需求量 ) * ($data->當月每日更換頻率 )  * ($data->本月生產天數 ) / ($data->MPQ );
                                $nextneedb = ($data->下月站位人數 ) * ($data->下月開線數 ) * ($data->下月開班數 ) *
                                ($data->下月每人每日需求量 ) * ($data->下月每日更換頻率 )  * ($data->下月生產天數 ) / ($data->MPQ );
                                $safeb = $nextneedb * ($data->LT ) /  ($data->MPQ ) / $data->下月生產天數;
                                $realneedb = $nextneedb + $nowneedb + $safeb - $stockb - $amountb;
                                $real = $realneedb < 0 ? 0:$realneedb;
                            ?>
                            <tr>
                                <td><input type="text" id="srmnumberb{{$loop->index}}" name="srmnumberb{{$loop->index}}" style="width:60px"></td>
                                <td><input type = "hidden" id = "clientb{{$loop->index}}" name = "clientb{{$loop->index}}" value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                                <td><input type = "hidden" id = "numberb{{$loop->index}}" name = "numberb{{$loop->index}}" value="{{$data->料號}}">{{$data->料號}}</td>
                                <td><input type = "hidden" id = "nameb{{$loop->index}}" name = "nameb{{$loop->index}}" value="{{$data->品名}}">{{$data->品名}}</td>
                                <td><input type = "hidden" id = "moqb{{$loop->index}}" name = "moqb{{$loop->index}}" value="{{$data->MOQ}}">{{$data->MOQ}}</td>
                                <td><input type = "hidden" id = "nextneedb{{$loop->index}}" name = "nextneedb{{$loop->index}}" value="{{$nextneedb}}">{{$nextneedb}}</td>
                                <td><input type = "hidden" id = "nowneedb{{$loop->index}}" name = "nowneedb{{$loop->index}}" value="{{$nowneedb}}">{{$nowneedb}}</td>
                                <td><input type = "hidden" id = "safeb{{$loop->index}}" name = "safeb{{$loop->index}}" value="{{$safeb}}">{{$safeb}}</td>
                                <td><input type = "hidden" id = "priceb{{$loop->index}}" name = "priceb{{$loop->index}}" value="{{$data->單價}}">{{$data->單價}}</td>
                                <td><input type = "hidden" id = "moneyb{{$loop->index}}" name = "moneyb{{$loop->index}}" value="{{$data->幣別}}">{{$data->幣別}}</td>
                                <td><input type = "hidden" id = "rateb{{$loop->index}}" name = "rateb{{$loop->index}}" value="{{$rate2[$loop->index]}}">{{$rate2[$loop->index]}}</td>
                                <td><input type = "hidden" id = "amountb{{$loop->index}}" name = "amountb{{$loop->index}}" value="{{$amountb}}">{{$amountb}}</td>
                                <td><input type = "hidden" id = "stockb{{$loop->index}}" name = "stockb{{$loop->index}}" value="{{$stockb}}">{{$stockb}}</td>
                                <td><input type = "number" id = "buyamountb{{$loop->index}}" name = "buyamountb{{$loop->index}}" required style="width:60px" value="{{$real}}"></td>
                                <td><input type = "hidden" id = "realneedb{{$loop->index}}" name = "realneedb{{$loop->index}}" value="{{$realneedb}}">{{$realneedb}}</td>
                                <td><input id = "buymoneyb{{$loop->index}}" name = "buymoneyb{{$loop->index}}" style="width:70px" readonly></td>
                                <td><input id = "buyperb{{$loop->index}}" name = "buyperb{{$loop->index}}" style="width:70px"  readonly step="0.01"></td>
                                <td><input id = "needmoneyb{{$loop->index}}" name = "needmoneyb{{$loop->index}}" style="width:70px" readonly></td>
                                <td><input id = "needperb{{$loop->index}}" name = "needperb{{$loop->index}}" style="width:70px" readonly step="0.01"></td>
                            </tr>
                            <input type = "hidden" id = "countb" name = "countb" value="{{$loop->count}}">
                            @endforeach
                        </table>
                            </div>
                        <!-- 單耗下載資料 -->
                        @foreach ($dow1 as $data )
                        <?php

                            $amounta = DB::table('在途量')->where('料號',$data->料號)->where('客戶',$data->客戶別)->sum('請購數量');
                            $stocka = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->sum('現有庫存');
                            $nextneeda = ($data->下月MPS ) * $data->單耗;
                            $nowneeda = ($data->本月MPS ) * $data->單耗;
                            $safea = $nextneeda * ($data->LT ) / $data->下月生產天數;
                            $realneeda = $nextneeda + $nowneeda + $safea - $stocka - $amounta;

                        ?>
                        <input type = "hidden" id="data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "">
                        <input type = "hidden" id="data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->客戶別}}">
                        <input type = "hidden" id="data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->料號}}">
                        <input type = "hidden" id="data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->品名}}">
                        <input type = "hidden" id="data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->MOQ}}">
                        <input type = "hidden" id="data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$nextneeda}}">
                        <input type = "hidden" id="data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$nowneeda}}">
                        <input type = "hidden" id="data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$safea}}">
                        <input type = "hidden" id="data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "{{$data->單價}}">
                        <input type = "hidden" id="data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$data->幣別}}">
                        <input type = "hidden" id="data10{{$loop->index}}" name = "data10{{$loop->index}}" value="{{$drate1[$loop->index]}}">
                        <input type = "hidden" id="data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$amounta}}">
                        <input type = "hidden" id="data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "{{$stocka}}">
                        <input type = "hidden" id="data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "{{$realneeda}}">
                        <input type = "hidden" id="data14{{$loop->index}}" name = "data14{{$loop->index}}" value = "{{$realneeda}}">
                        <input type = "hidden" id="data15{{$loop->index}}" name = "data15{{$loop->index}}" value = "">
                        <input type = "hidden" id="data16{{$loop->index}}" name = "data16{{$loop->index}}" value = "">
                        <input type = "hidden" id="data17{{$loop->index}}" name = "data17{{$loop->index}}" value = "">
                        <input type = "hidden" id="data18{{$loop->index}}" name = "data18{{$loop->index}}" value = "">
                        @endforeach

                        <!-- 站位下載資料 -->
                        @foreach ($dow2 as $data )
                        <?php

                            $amountb = DB::table('在途量')->where('料號',$data->料號)->where('客戶',$data->客戶別)->sum('請購數量');
                            $stockb = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->sum('現有庫存');
                            $nowneedb = ($data->當月站位人數 ) * ($data->當月開線數 ) * ($data->當月開班數 ) *
                            ($data->當月每人每日需求量 ) * ($data->當月每日更換頻率 )  * ($data->本月生產天數 ) / ($data->MPQ );
                            $nextneedb = ($data->下月站位人數 ) * ($data->下月開線數 ) * ($data->下月開班數 ) *
                            ($data->下月每人每日需求量 ) * ($data->下月每日更換頻率 )  * ($data->下月生產天數 ) / ($data->MPQ );
                            $safeb = $nextneedb * ($data->LT ) /  ($data->MPQ ) / $data->下月生產天數;
                            $realneedb = $nextneedb + $nowneedb + $safeb - $stockb - $amountb;

                        ?>
                        <input type = "hidden" id="dataa0{{$loop->index}}" name = "dataa0{{$loop->index}}" value = "">
                        <input type = "hidden" id="dataa1{{$loop->index}}" name = "dataa1{{$loop->index}}" value = "{{$data->客戶別}}">
                        <input type = "hidden" id="dataa2{{$loop->index}}" name = "dataa2{{$loop->index}}" value = "{{$data->料號}}">
                        <input type = "hidden" id="dataa3{{$loop->index}}" name = "dataa3{{$loop->index}}" value = "{{$data->品名}}">
                        <input type = "hidden" id="dataa4{{$loop->index}}" name = "dataa4{{$loop->index}}" value = "{{$data->MOQ}}">
                        <input type = "hidden" id="dataa5{{$loop->index}}" name = "dataa5{{$loop->index}}" value = "{{$nextneedb}}">
                        <input type = "hidden" id="dataa6{{$loop->index}}" name = "dataa6{{$loop->index}}" value = "{{$nowneedb}}">
                        <input type = "hidden" id="dataa7{{$loop->index}}" name = "dataa7{{$loop->index}}" value = "{{$safeb}}">
                        <input type = "hidden" id="dataa8{{$loop->index}}" name = "dataa8{{$loop->index}}" value = "{{$data->單價}}">
                        <input type = "hidden" id="dataa9{{$loop->index}}" name = "dataa9{{$loop->index}}" value = "{{$data->幣別}}">
                        <input type = "hidden" id="dataa10{{$loop->index}}" name = "dataa10{{$loop->index}}" value="{{$drate2[$loop->index]}}">
                        <input type = "hidden" id="dataa11{{$loop->index}}" name = "dataa11{{$loop->index}}" value = "{{$amountb}}">
                        <input type = "hidden" id="dataa12{{$loop->index}}" name = "dataa12{{$loop->index}}" value = "{{$stockb}}">
                        <input type = "hidden" id="dataa13{{$loop->index}}" name = "dataa13{{$loop->index}}" value = "{{$realneedb}}">
                        <input type = "hidden" id="dataa14{{$loop->index}}" name = "dataa14{{$loop->index}}" value = "{{$realneedb}}">
                        <input type = "hidden" id="dataa15{{$loop->index}}" name = "dataa15{{$loop->index}}" value = "">
                        <input type = "hidden" id="dataa16{{$loop->index}}" name = "dataa16{{$loop->index}}" value = "">
                        <input type = "hidden" id="dataa17{{$loop->index}}" name = "dataa17{{$loop->index}}" value = "">
                        <input type = "hidden" id="dataa18{{$loop->index}}" name = "dataa18{{$loop->index}}" value = "">
                        @endforeach

                        <br>
                        <input type = "submit" id = "inser" name = "insert" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}">
                        <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.export') !!}">
                        <input type = "submit" id = "download1" name = "download1" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.export1') !!}">
                        </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.buylist')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
