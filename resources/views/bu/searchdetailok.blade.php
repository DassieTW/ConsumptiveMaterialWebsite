@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/bu/searchlist.js') }}"></script>
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
                <h3>{!! __('bupagelang.searchdetail') !!}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "調撥單號">{!! __('bupagelang.dblist') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "撥出廠區">{!! __('bupagelang.outfactory') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "接收廠區">{!! __('bupagelang.receivefac') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "客戶別">{!! __('bupagelang.client') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "料號">{!! __('bupagelang.isn') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "品名">{!! __('bupagelang.pName') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "規格">{!! __('bupagelang.format') !!}</th>
                                @if ($choose === 'out')
                                <th><input type = "hidden" id = "title7" name = "title7" value = "現有庫存">{!! __('bupagelang.nowstock') !!}</th>
                                @else
                                <th><input type = "hidden" id = "title7" name = "title7" value = "實際接收數量">{!! __('bupagelang.realpickamount') !!}</th>
                                @endif
                                <th><input type = "hidden" id = "title8" name = "title8" value = "實際撥出數量">{!! __('bupagelang.realamount') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title9" value = "儲位">{!! __('bupagelang.loc') !!}</th>
                                @if ($choose === 'out')
                                <th><input type = "hidden" id = "title10" name = "title10" value = "預計撥出數量">{!! __('bupagelang.preamount') !!}</th>
                                @endif
                                @if ($choose === 'out')
                                <th><input type = "hidden" id = "title11" name = "title11" value = "調撥人">{!! __('bupagelang.transpeople') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title12" value = "撥出時間">{!! __('bupagelang.outtime') !!}</th>
                                @else
                                <th><input type = "hidden" id = "title11" name = "title11" value = "接收人">{!! __('bupagelang.receivepeople') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title12" value = "接收時間">{!! __('bupagelang.receivetime') !!}</th>
                                @endif
                            </tr>
                                @foreach($data as $data)
                                <tr id= "{{$loop->index}}">
                                    <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->調撥單號}}">{{$data->調撥單號}}</td>
                                    <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->撥出廠區}}">{{$data->撥出廠區}}</td>
                                    <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->接收廠區}}">{{$data->接收廠區}}</td>
                                    <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    @if ($choose === 'out')
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$data->現有庫存}}">{{$data->現有庫存}}</td>
                                    @else
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$data->實際接收數量}}">{{$data->實際接收數量}}</td>
                                    @endif
                                    <td><input type = "hidden" id = "data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "{{$data->實際撥出數量}}">{{$data->實際撥出數量}}</td>
                                    <td><input type = "hidden" id = "data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$data->儲位}}">{{$data->儲位}}</td>
                                    @if ($choose === 'out')
                                    <td><input type = "hidden" id = "data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "{{$data->預計撥出數量}}">{{$data->預計撥出數量}}</td>
                                    <td><input type = "hidden" id = "data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$data->調撥人}}">{{$data->調撥人}}</td>
                                    <td><input type = "hidden" id = "data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "{{$data->撥出時間}}">{{$data->撥出時間}}</td>
                                    @else
                                    <td><input type = "hidden" id = "data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "{{$data->接收人}}">{{$data->接收人}}</td>
                                    <td><input type = "hidden" id = "data14{{$loop->index}}" name = "data14{{$loop->index}}" value = "{{$data->接收時間}}">{{$data->接收時間}}</td>
                                    @endif

                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                        </div>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.searchdetail')}}'">{!! __('bupagelang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
