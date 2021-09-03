@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('/js/safe.js') }}"></script>
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

                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <div class="table-responsive">
                            <table class="table table-bordered" id = "test">
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
                                    @if($stock < $data->安全庫存)
                                    <tr id = "data1{{$loop->index}}">
                                        @if($data->月請購 === '是')
                                        <td>{{$data->客戶別}}</td>
                                        @else
                                        <td>{!! __('callpageLang.notmonth') !!}</td>
                                        @endif
                                        <td>{{$data->料號}}</td>
                                        <td>{{$data->品名}}</td>
                                        <td>{{$data->規格}}</td>
                                        <td id = "stocka{{$loop->index}}">{{$stock}}</td>
                                        <td id = "safea{{$loop->index}}">{{$data->安全庫存}}</td>
                                        @if($stock === 0 || $stock === null)
                                        <td>{!! __('callpageLang.nostock') !!}</td>
                                        @else
                                        <td>
                                        @foreach ($test as $k=> $a)
                                        {!! __('callpageLang.loc') !!} : {{$k}} {!! __('callpageLang.nowstock') !!} : {{$a}}<br>
                                        @endforeach
                                        </td>
                                        @endif
                                        <td><input type = "text" style="width:100px"></td>
                                    </tr>
                                    @endif
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                    @endforeach

                                    @foreach($data1 as $data)
                                    <?php
                                        $stock = DB::table('inventory')->where('客戶別', $data->客戶別)->where('料號', $data->料號)->sum('現有庫存');
                                        $astock = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('現有庫存')->toArray();
                                        $position = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('儲位')->toArray();
                                        $test = array_combine($position, $astock);
                                    ?>
                                    @if($stock < $data->安全庫存)
                                    <tr id = "data2{{$loop->index}}">
                                        @if($data->月請購 === '是')
                                        <td>{{$data->客戶別}}</td>
                                        @else
                                        <td>{!! __('callpageLang.notmonth') !!}</td>
                                        @endif
                                        <td>{{$data->料號}}</td>
                                        <td>{{$data->品名}}</td>
                                        <td>{{$data->規格}}</td>
                                        <td id = "stockb{{$loop->index}}">{{$stock}}</td>
                                        <td id = "safeb{{$loop->index}}">{{$data->安全庫存}}</td>
                                        @if($stock === 0 || $stock === null)
                                        <td>{!! __('callpageLang.nostock') !!}</td>
                                        @else
                                        <td>
                                        @foreach ($test as $k=> $a)
                                        {!! __('callpageLang.loc') !!} : {{$k}} {!! __('callpageLang.nowstock') !!} : {{$a}}<br>
                                        @endforeach
                                        </td>
                                        @endif
                                        <td><input type = "text" style="width:100px"></td>
                                    </tr>
                                    @endif
                                    <input type = "hidden" id="count1" name = "count1" value="{{$loop->count}}">
                                    @endforeach


                            </table>

                        </div>
                    </div>
                </div>

                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('call.safe')}}'">{!! __('callpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
