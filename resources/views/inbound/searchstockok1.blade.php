@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.inbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.stockmonth') !!}{!! __('inboundpageLang.search') !!}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                        <form action="{{ route('inbound.download') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "庫存">
                        <table class="table" id = "inboundsearch">
                            <tr id = "require">
                                <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('inboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('inboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "品名">{!! __('inboundpageLang.pName') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "規格">{!! __('inboundpageLang.format') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title4" value = "單位">{!! __('inboundpageLang.unit') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title5" value = "單價">{!! __('inboundpageLang.price') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title6" value = "幣別">{!! __('inboundpageLang.money') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title7" value = "A級資材">{!! __('inboundpageLang.gradea') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title8" value = "月請購">{!! __('inboundpageLang.month') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title9" value = "現有庫存">{!! __('inboundpageLang.nowstock') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title10" value = "月使用量">{!! __('inboundpageLang.monthuse') !!}</th>
                                <th><input type = "hidden" id = "title11" name = "title11" value = "庫存使用月數">{!! __('inboundpageLang.stockmonth') !!}</th>
                                <input type = "hidden" id = "time" name = "time" value = "12">
                            </tr>
                                @foreach($data as $data)
                                <tr>
                                    <?php
                                        $name = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                                        $format = DB::table('consumptive_material')->where('料號',$data->料號)->value('規格');
                                        $unit = DB::table('consumptive_material')->where('料號',$data->料號)->value('單位');
                                        $price = DB::table('consumptive_material')->where('料號',$data->料號)->value('單價');
                                        $money = DB::table('consumptive_material')->where('料號',$data->料號)->value('幣別');
                                        $gradea = DB::table('consumptive_material')->where('料號',$data->料號)->value('A級資材');
                                        $month = DB::table('consumptive_material')->where('料號',$data->料號)->value('月請購');
                                        $belong = DB::table('consumptive_material')->where('料號',$data->料號)->value('耗材歸屬');
                                        $lt = DB::table('consumptive_material')->where('料號',$data->料號)->value('LT');
                                        if($belong === '單耗')
                                            {
                                                $machine = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('機種');
                                                $production = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('製程');
                                                $consume = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('單耗');
                                                $nowmps = DB::table('MPS')->where('機種',$machine)->where('客戶別',$data->客戶別)->where('製程',$production)->value('本月MPS');

                                                $monthuse = $consume * $nowmps ;
                                                if($monthuse != 0)
                                                {
                                                    $stockmonth = $data->現有庫存 / $monthuse;
                                                }
                                                else
                                                {
                                                    $stockmonth = 0;
                                                }
                                            }
                                            else
                                            {
                                                $machine = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('機種');
                                                $production = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('製程');
                                                $nowday = DB::table('MPS')->where('客戶別',$data->客戶別)->value('本月生產天數');
                                                $nowstand = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('當月站位人數');
                                                $nowline = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('當月開線數');
                                                $nowclass = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('當月開班數');
                                                $nowneed = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('當月每人每日需求量');
                                                $nowchange = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->value('當月每日更換頻率');
                                                $mpq = DB::table('consumptive_material')->where('料號',$data->料號)->value('MPQ');

                                                if($mpq != 0 || $mpq != null)
                                                {
                                                    $monthuse = ($nowday * $nowstand * $nowline * $nowclass * $nowneed * $nowchange / $mpq);
                                                }
                                                else
                                                {
                                                    $monthuse = 0;

                                                }

                                                if($monthuse !=0)
                                                {
                                                    $stockmonth = $data->現有庫存 / $monthuse;
                                                }
                                                else
                                                {
                                                    $stockmonth = 0;
                                                }

                                            }
                                    ?>
                                    <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$name}}">{{$name}}</td>
                                    <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$format}}">{{$format}}</td>
                                    <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$unit}}">{{$unit}}</td>
                                    <td><input type = "hidden" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$price}}">{{$price}}</td>
                                    <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$money}}">{{$money}}</td>
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$gradea}}">{{$gradea}}</td>
                                    <td><input type = "hidden" id = "data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "{{$month}}">{{$month}}</td>
                                    <td><input type = "hidden" id = "data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$data->現有庫存}}">{{$data->現有庫存}}</td>
                                    <td><input type = "hidden" id = "data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "{{$monthuse}}">{{$monthuse}}</td>
                                    <td><input type = "hidden" id = "data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$stockmonth}}">{{$stockmonth}}</td>
                                </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                    @endforeach

                                </table>
                            </div>
                            <br>
                                <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.download') !!}">
                            </form>
                    <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.searchstock')}}'">{!! __('inboundpageLang.return') !!}</button>
                </div>
            </div>
    </html>
    @endsection
