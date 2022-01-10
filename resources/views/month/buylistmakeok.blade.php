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
        <form method="POST" id="buylist">
            @csrf
            <input type="hidden" id="titlename" name="titlename" value="請購單">
            <input type="hidden" id="titlename1" name="titlename1" value="請購單上傳">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td><input type="hidden" id="title0" name="title0" value="SRM單號">{!! __('monthlyPRpageLang.srm')
                            !!}</td>
                        <td><input type="hidden" id="title1" name="title1" value="客戶別">{!!
                            __('monthlyPRpageLang.client') !!}</td>
                        <td><input type="hidden" id="title2" name="title2" value="料號">{!! __('monthlyPRpageLang.isn')
                            !!}</td>
                        <td><input type="hidden" id="title3" name="title3" value="品名">{!! __('monthlyPRpageLang.pName')
                            !!}</td>
                        <td><input type="hidden" id="title4" name="title4" value="MOQ">{!! __('monthlyPRpageLang.moq')
                            !!}</td>
                        <td><input type="hidden" id="title5" name="title5" value="下月需求">{!!
                            __('monthlyPRpageLang.nextneed') !!}</td>
                        <td><input type="hidden" id="title6" name="title6" value="當月需求">{!!
                            __('monthlyPRpageLang.nowneed') !!}</td>
                        <td><input type="hidden" id="title7" name="title7" value="安全庫存">{!! __('monthlyPRpageLang.safe')
                            !!}</td>
                        <td><input type="hidden" id="title8" name="title8" value="單價">{!! __('monthlyPRpageLang.price')
                            !!}</td>
                        <td><input type="hidden" id="title9" name="title9" value="幣別">{!! __('monthlyPRpageLang.money')
                            !!}</td>
                        <td><input type="hidden" id="title10" name="title10" value="匯率">{!! __('monthlyPRpageLang.rate')
                            !!}</td>
                        <td><input type="hidden" id="title11" name="title11" value="在途數量">{!!
                            __('monthlyPRpageLang.transit') !!}</td>
                        <td><input type="hidden" id="title12" name="title12" value="現有庫存">{!!
                            __('monthlyPRpageLang.nowstock') !!}</td>
                        <td><input type="hidden" id="title13" name="title13" value="本次請購數量">{!!
                            __('monthlyPRpageLang.buyamount') !!}</td>
                        <td><input type="hidden" id="title14" name="title14" value="實際需求">{!!
                            __('monthlyPRpageLang.realneed') !!}</td>
                        <td><input type="hidden" id="title15" name="title15" value="請購金額">{!!
                            __('monthlyPRpageLang.buyprice') !!}</td>
                        <td><input type="hidden" id="title16" name="title16" value="請購占比">{!!
                            __('monthlyPRpageLang.buyper') !!}</td>
                        <td><input type="hidden" id="title17" name="title17" value="需求金額">{!!
                            __('monthlyPRpageLang.needprice') !!}</td>
                        <td><input type="hidden" id="title18" name="title18" value="需求占比">{!!
                            __('monthlyPRpageLang.needper') !!}</td>
                        <input type="hidden" id="titlecount" name="titlecount" value="19">
                    </tr>
                    @foreach($data1 as $data)
                    <?php
                            $amounta = DB::table('在途量')->where('料號',$data->料號)->where('客戶',$data->客戶別)->sum('請購數量');
                            $stocka = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->sum('現有庫存');
                            $amounta = round($amounta,0);
                            $nextneeda = ($data->下月MPS ) * $data->單耗;
                            $nowneeda = ($data->本月MPS ) * $data->單耗;
                            $safea = $nextneeda * ($data->LT ) / $data->下月生產天數;
                            $realneeda = $nextneeda + $nowneeda + $safea - $stocka - $amounta;
                            $realneeda = round($realneeda,0);
                            $real = $realneeda < 0 ? 0:$realneeda;
                    ?>
                    <tr>
                        <td><input class="form-control form-control-lg" type="text" id="srmnumbera{{$loop->index}}"
                                name="srmnumbera{{$loop->index}}" style="width:100px"></td>
                        <td><input type="hidden" id="clienta{{$loop->index}}" name="clienta{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="numbera{{$loop->index}}" name="numbera{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="namea{{$loop->index}}" name="namea{{$loop->index}}"
                                value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="moqa{{$loop->index}}" name="moqa{{$loop->index}}"
                                value="{{$data->MOQ}}">{{$data->MOQ}}</td>
                        <td><input type="hidden" id="nextneeda{{$loop->index}}" name="nextneeda{{$loop->index}}"
                                value="{{$nextneeda}}">{{$nextneeda}}</td>
                        <td><input type="hidden" id="nowneeda{{$loop->index}}" name="nowneeda{{$loop->index}}"
                                value="{{$nowneeda}}">{{$nowneeda}}</td>
                        <td><input type="hidden" id="safea{{$loop->index}}" name="safea{{$loop->index}}"
                                value="{{$safea}}">{{$safea}}</td>
                        <td><input type="hidden" id="pricea{{$loop->index}}" name="pricea{{$loop->index}}"
                                value="{{$data->單價}}">{{$data->單價}}</td>
                        <td><input type="hidden" id="moneya{{$loop->index}}" name="moneya{{$loop->index}}"
                                value="{{$data->幣別}}">{{$data->幣別}}</td>
                        <td><input type="hidden" id="ratea{{$loop->index}}" name="ratea{{$loop->index}}"
                                value="{{$rate1[$loop->index]}}">{{$rate1[$loop->index]}}</td>
                        <td><input type="hidden" id="amounta{{$loop->index}}" name="amounta{{$loop->index}}"
                                value="{{$amounta}}">{{$amounta}}</td>
                        <td><input type="hidden" id="stocka{{$loop->index}}" name="stocka{{$loop->index}}"
                                value="{{$stocka}}">{{$stocka}}</td>
                        <td><input class="form-control form-control-lg" type="number" id="buyamounta{{$loop->index}}"
                                name="buyamounta{{$loop->index}}" required value="{{$real}}" min="0"
                                style="width:100px"></td>
                        <td><input type="hidden" id="realneeda{{$loop->index}}" name="realneeda{{$loop->index}}"
                                value="{{$realneeda}}">{{$realneeda}}</td>
                        <td><input class="form-control form-control-lg" id="buymoneya{{$loop->index}}"
                                name="buymoneya{{$loop->index}}" style="width:100px" readonly></td>
                        <td><input class="form-control form-control-lg" id="buypera{{$loop->index}}"
                                name="buypera{{$loop->index}}" style="width:100px" readonly step="0.01"></td>
                        <td><input class="form-control form-control-lg" id="needmoneya{{$loop->index}}"
                                name="needmoneya{{$loop->index}}" style="width:100px" readonly></td>
                        <td><input class="form-control form-control-lg" id="needpera{{$loop->index}}"
                                name="needpera{{$loop->index}}" style="width:100px" readonly step="0.01"></td>
                    </tr>
                    <input type="hidden" id="counta" name="counta" value="{{$loop->count}}">
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
                        $realneedb = round($realneedb,0);
                        $real = $realneedb < 0 ? 0:$realneedb;
                    ?>
                    <tr>
                        <td><input class="form-control form-control-lg" type="text" id="srmnumberb{{$loop->index}}"
                                name="srmnumberb{{$loop->index}}" style="width:100px"></td>
                        <td><input type="hidden" id="clientb{{$loop->index}}" name="clientb{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="numberb{{$loop->index}}" name="numberb{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="nameb{{$loop->index}}" name="nameb{{$loop->index}}"
                                value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="moqb{{$loop->index}}" name="moqb{{$loop->index}}"
                                value="{{$data->MOQ}}">{{$data->MOQ}}</td>
                        <td><input type="hidden" id="nextneedb{{$loop->index}}" name="nextneedb{{$loop->index}}"
                                value="{{$nextneedb}}">{{$nextneedb}}</td>
                        <td><input type="hidden" id="nowneedb{{$loop->index}}" name="nowneedb{{$loop->index}}"
                                value="{{$nowneedb}}">{{$nowneedb}}</td>
                        <td><input type="hidden" id="safeb{{$loop->index}}" name="safeb{{$loop->index}}"
                                value="{{$safeb}}">{{$safeb}}</td>
                        <td><input type="hidden" id="priceb{{$loop->index}}" name="priceb{{$loop->index}}"
                                value="{{$data->單價}}">{{$data->單價}}</td>
                        <td><input type="hidden" id="moneyb{{$loop->index}}" name="moneyb{{$loop->index}}"
                                value="{{$data->幣別}}">{{$data->幣別}}</td>
                        <td><input type="hidden" id="rateb{{$loop->index}}" name="rateb{{$loop->index}}"
                                value="{{$rate2[$loop->index]}}">{{$rate2[$loop->index]}}</td>
                        <td><input type="hidden" id="amountb{{$loop->index}}" name="amountb{{$loop->index}}"
                                value="{{$amountb}}">{{$amountb}}</td>
                        <td><input type="hidden" id="stockb{{$loop->index}}" name="stockb{{$loop->index}}"
                                value="{{$stockb}}">{{$stockb}}</td>
                        <td><input class="form-control form-control-lg" type="number" id="buyamountb{{$loop->index}}"
                                name="buyamountb{{$loop->index}}" required value="{{$real}}" min="0"
                                style="width:100px"></td>
                        <td><input type="hidden" id="realneedb{{$loop->index}}" name="realneedb{{$loop->index}}"
                                value="{{$realneedb}}">{{$realneedb}}</td>
                        <td><input class="form-control form-control-lg" id="buymoneyb{{$loop->index}}"
                                name="buymoneyb{{$loop->index}}" style="width:100px" readonly></td>
                        <td><input class="form-control form-control-lg" id="buyperb{{$loop->index}}"
                                name="buyperb{{$loop->index}}" style="width:100px" readonly step="0.01"></td>
                        <td><input class="form-control form-control-lg" id="needmoneyb{{$loop->index}}"
                                name="needmoneyb{{$loop->index}}" style="width:100px" readonly></td>
                        <td><input class="form-control form-control-lg" id="needperb{{$loop->index}}"
                                name="needperb{{$loop->index}}" style="width:100px" readonly step="0.01"></td>
                    </tr>
                    <input type="hidden" id="countb" name="countb" value="{{$loop->count}}">
                    @endforeach
                </table>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <input type="submit" id="inser" name="insert" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.submit') !!}">
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.export') !!}">
            <input type="submit" id="download1" name="download1" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.export1') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <button class="btn btn-primary" id="writesrm">{!! __('monthlyPRpageLang.writesrm') !!}</button>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.buylist')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
