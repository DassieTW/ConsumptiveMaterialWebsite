@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('/js/call/safe.js') }}"></script>
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('callpageLang.callsys') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('callpageLang.safealert') !!}</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="test">
                <tr>
                    <th>{!! __('callpageLang.client') !!}</th>
                    <th>{!! __('callpageLang.isn') !!}</th>
                    <th>{!! __('callpageLang.pName') !!}</th>
                    <th>{!! __('callpageLang.format') !!}</th>
                    <th>{!! __('callpageLang.stock') !!}</th>
                    <th>{!! __('callpageLang.safe') !!}</th>
                    <th>{!! __('callpageLang.loc') !!}</th>
                    <th>{!! __('callpageLang.mark') !!}</th>

                </tr>
                @foreach($data as $data)
                <?php
                    $stock = DB::table('inventory')->where('客戶別', $data->客戶別)->where('料號', $data->料號)->sum('現有庫存');
                    $astock = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('現有庫存')->toArray();
                    $position = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('儲位')->toArray();
                    $test = array_combine($position, $astock);

                ?>

                <tr id="data{{$loop->index}}">
                    <td>{{$data->客戶別}}</td>
                    <td>{{$data->料號}}</td>
                    <td>{{$data->品名}}</td>
                    <td>{{$data->規格}}</td>
                    <td id="stocka{{$loop->index}}">{{$stock}}</td>
                    <td id="safea{{$loop->index}}">{{$data->安全庫存}}</td>
                    @if($stock === 0 || $stock === null)
                    <td>{!! __('callpageLang.nostock') !!}</td>
                    @else
                    <td>
                        @foreach ($test as $k=> $a)
                        @if ($a > 0)
                        {!! __('callpageLang.loc') !!} : {{$k}} {!! __('callpageLang.nowstock') !!} : {{$a}}<br>
                        @endif
                        @endforeach
                    </td>
                    @endif
                    <td><input class="form-control form-control-lg" type="text" style="width:100px"></td>
                </tr>

                <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                @endforeach

                @foreach($data1 as $data)
                <?php
                    $stock1 = DB::table('inventory')->where('客戶別', $data->客戶別)->where('料號', $data->料號)->sum('現有庫存');
                    $astock1 = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('現有庫存')->toArray();
                    $position1 = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('儲位')->toArray();
                    $test1 = array_combine($position1, $astock1);
                ?>

                <tr id="data1{{$loop->index}}">
                    <td>{{$data->客戶別}}</td>
                    <td>{{$data->料號}}</td>
                    <td>{{$data->品名}}</td>
                    <td>{{$data->規格}}</td>
                    <td id="stockb{{$loop->index}}">{{$stock1}}</td>
                    <td id="safeb{{$loop->index}}">{{$data->安全庫存}}</td>
                    @if($stock1 === 0 || $stock1 === null)
                    <td>{!! __('callpageLang.nostock') !!}</td>
                    @else
                    <td>
                        @foreach ($test1 as $k=> $a)
                        @if($a > 0)
                        {!! __('callpageLang.loc') !!} : {{$k}} {!! __('callpageLang.nowstock') !!} : {{$a}}<br>
                        @endif
                        @endforeach
                    </td>
                    @endif
                    <td><input class="form-control form-control-lg" type="text" style="width:100px"></td>
                </tr>

                <input type="hidden" id="count1" name="count1" value="{{$loop->count}}">
                @endforeach

                @foreach($data2 as $data)
                <?php
                    $stock2 = DB::table('inventory')->where('料號', $data->料號)->sum('現有庫存');
                    $astock2 = DB::table('inventory')->select('現有庫存')->where('料號',$data->料號)->get()->toArray();
                    $position2 = DB::table('inventory')->select('儲位')->where('料號',$data->料號)->get()->toArray();
                    // dd($astock2 , $position2);
                ?>

                <tr id="data2{{$loop->index}}">
                    <td>{!! __('callpageLang.notmonth') !!}</td>
                    <td>{{$data->料號}}</td>
                    <td>{{$data->品名}}</td>
                    <td>{{$data->規格}}</td>
                    <td id="stockc{{$loop->index}}">{{$stock2}}</td>
                    <td id="safec{{$loop->index}}">{{$data->安全庫存}}</td>
                    @if($stock2 === 0 || $stock2 === null)
                    <td>{!! __('callpageLang.nostock') !!}</td>
                    @else
                    <td>
                        @foreach ($astock2 as $k=> $a)
                        {!! __('callpageLang.loc') !!} : {{$position2[$k]->儲位}} {!! __('callpageLang.nowstock') !!} : {{$a->現有庫存}}<br>
                        @endforeach
                    </td>
                    @endif
                    <td><input class="form-control form-control-lg" type="text" style="width:100px"></td>
                </tr>

                <input type="hidden" id="count2" name="count2" value="{{$loop->count}}">
                @endforeach


            </table>

        </div>
        <br>

        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('call.safe')}}'">{!!
            __('callpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
