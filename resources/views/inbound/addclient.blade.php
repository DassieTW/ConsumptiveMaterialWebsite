@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/addclient.js') }}"></script>
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
        <h3>{!! __('inboundpageLang.addclient') !!}</h3>
    </div>
    <div class="card-body">
        <form id="addclient" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <tr id="require">
                        <th>{!! __('inboundpageLang.client') !!}</th>
                        <th>{!! __('inboundpageLang.isn') !!}</th>
                        <th>{!! __('inboundpageLang.pName') !!}</th>
                        <th>{!! __('inboundpageLang.format') !!}</th>
                        <th>{!! __('inboundpageLang.unit') !!}</th>
                        <th>{!! __('inboundpageLang.transit') !!}</th>
                        <th>{!! __('inboundpageLang.nowstock') !!}</th>
                        <th>{!! __('inboundpageLang.safe') !!}</th>
                        <th>{!! __('inboundpageLang.inboundnum') !!}</th>
                        <th>{!! __('inboundpageLang.inreason') !!}</th>
                        <th>{!! __('inboundpageLang.oldloc') !!}</th>
                        <th>{!! __('inboundpageLang.newloc') !!}</th>
                    </tr>
                    @foreach($data as $data)
                    <tr>
                        <?php
                            $data->請購數量 = round($data->請購數量,0);
                            $stock = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶)->sum('現有庫存');
                            $posit = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶)->where('現有庫存','>',0)->pluck('儲位');
                            $name = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                            $format = DB::table('consumptive_material')->where('料號',$data->料號)->value('規格');
                            $unit = DB::table('consumptive_material')->where('料號',$data->料號)->value('單位');
                            $belong = DB::table('consumptive_material')->where('料號',$data->料號)->value('耗材歸屬');
                            $lt = DB::table('consumptive_material')->where('料號',$data->料號)->value('LT');
                            if($belong === '單耗')
                            {
                                $machine = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('機種');
                                $production = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('製程');
                                $consume = DB::table('月請購_單耗')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('單耗');
                                $nextmps = DB::table('MPS')->where('機種',$machine)->where('客戶別',$data->客戶)->where('製程',$production)->value('下月MPS');
                                $nextday = DB::table('MPS')->where('機種',$machine)->where('客戶別',$data->客戶)->where('製程',$production)->value('下月生產天數');

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
                                $machine = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('機種');
                                $production = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('製程');
                                $nextstand = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('下月站位人數');
                                $nextline = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('下月開線數');
                                $nextclass = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('下月開班數');
                                $nextuse = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('下月每人每日需求量');
                                $nextchange = DB::table('月請購_站位')->where('料號',$data->料號)->where('客戶別',$data->客戶)->value('下月每日更換頻率');
                                $mpq = DB::table('consumptive_material')->where('料號',$data->料號)->value('MPQ');
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
                        <td><input type="hidden" id="client{{$loop->index}}" name="client{{$loop->index}}"
                                value="{{$data->客戶}}">{{$data->客戶}}</td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="name{{$loop->index}}" name="name{{$loop->index}}"
                                value="{{$name}}">{{$name}}</td>
                        <td><input type="hidden" id="format{{$loop->index}}" name="format{{$loop->index}}"
                                value="{{$format}}">{{$format}}</td>
                        <td><input type="hidden" id="unit{{$loop->index}}" name="unit{{$loop->index}}"
                                value="{{$unit}}">{{$unit}}</td>
                        <td><input type="hidden" id="buyamount{{$loop->index}}" name="buyamount{{$loop->index}}"
                                value="{{$data->請購數量}}">{{$data->請購數量}}</td>
                        <td><input type="hidden" id="stock{{$loop->index}}" name="stock{{$loop->index}}"
                                value="{{$stock}}">{{$stock}}</td>
                        <td>{{$safe}}</td>
                        <td><input type="number" id="amount{{$loop->index}}" name="amount{{$loop->index}}" required
                                placeholder="{!! __('inboundpageLang.enteramount') !!}" min="0"></td>
                        <td><input type="hidden" id="inreason{{$loop->index}}" name="inreason{{$loop->index}}"
                                value="{{$inreason}}">{{$inreason}}</td>
                        <td>
                            <input type="hidden" id="oldposition{{$loop->index}}" name="oldposition{{$loop->index}}"
                                value="{{$posit}}">
                            @foreach($posit as $oldloc)
                            <span style="width: 100px; display: inline-block;">
                                {{ $oldloc }}</span>
                            @endforeach
                        </td>
                        <td>
                            <select class="form-select form-select-lg" id="position{{$loop->index}}"
                                name="position{{$loop->index}}" width="250" style="width: 250px" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('inboundpageLang.enterloc') !!}</option>
                                @foreach($positions as $position)
                                <option>{{ $position->儲存位置 }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach

                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="mb-3 col-md-6">
                <label class="form-label">{!! __('inboundpageLang.inpeople') !!}</label>
                <input class="form-control form-control-lg" id="inpeople" name="inpeople" required width="250"
                    style="width: 250px" placeholder="{!! __('inboundpageLang.enterinpeople') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <ul id="inboundmenu" style="display: none;" class="list-group">
                    @foreach($peopleinf as $peopleinf )
                    <a class="inboundlist list-group-item list-group-item-action" href="#">{{ $peopleinf ->工號 .' '.
                        $peopleinf ->姓名 }}</a>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    @endforeach
                </ul>
                @foreach($check as $people)
                <input type="hidden" id="checkpeople{{$loop->index}}" name="checkpeople{{$loop->index}}"
                    value="{{$people->工號}}">
                <input type="hidden" id="checkcount" name="checkcount" value="{{$loop->count}}">
                @endforeach
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('inboundpageLang.submit') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.add')}}'">{!!
            __('inboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
