@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('js/basic/material.js') }}"></script>
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('basicInfoLang.matssearch') !!}</h3>
    </div>
    <div class="card-body">

        <form id="materialsearch" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table " id="test">
                    <tr>
                        <th>{!! __('basicInfoLang.check') !!}</th>
                        <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('basicInfoLang.isn') !!}
                        </th>
                        <th><input type="hidden" id="title1" name="title1" value="品名">{!! __('basicInfoLang.pName') !!}
                        </th>
                        <th><input type="hidden" id="title2" name="title2" value="規格">{!! __('basicInfoLang.format') !!}
                        </th>
                        <th><input type="hidden" id="title3" name="title3" value="A級資材">{!! __('basicInfoLang.gradea')
                            !!}</th>
                        <th><input type="hidden" id="title4" name="title4" value="月請購">{!! __('basicInfoLang.month') !!}
                        </th>
                        <th><input type="hidden" id="title5" name="title5" value="發料部門">{!! __('basicInfoLang.senddep')
                            !!}</th>
                        <th><input type="hidden" id="title6" name="title6" value="耗材歸屬">{!! __('basicInfoLang.belong')
                            !!}</th>
                        <th><input type="hidden" id="title7" name="title7" value="單價">{!! __('basicInfoLang.price') !!}
                        </th>
                        <th><input type="hidden" id="title8" name="title8" value="幣別">{!! __('basicInfoLang.money') !!}
                        </th>
                        <th><input type="hidden" id="title9" name="title9" value="單位">{!! __('basicInfoLang.unit') !!}
                        </th>
                        <th><input type="hidden" id="title10" name="title10" value="MPQ">{!! __('basicInfoLang.mpq') !!}
                        </th>
                        <th><input type="hidden" id="title11" name="title11" value="MOQ">{!! __('basicInfoLang.moq') !!}
                        </th>
                        <th><input type="hidden" id="title12" name="title12" value="LT">{!! __('basicInfoLang.lt') !!}
                        </th>
                        <th><input type="hidden" id="title13" name="title13" value="安全庫存">{!! __('basicInfoLang.safe')
                            !!}</th>
                        <input type="hidden" id="time" name="time" value="14">
                    </tr>
                    @foreach($data as $data)
                    <tr>
                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                style="width:25px;height:25px;" value="{{$loop->index}}"></td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td>{{$data->品名}}</td>
                        <td>{{$data->規格}}</td>
                        <td>
                            <select style="width:100px" class="form-select form-select-lg " id="gradea{{$loop->index}}"
                                name="gradea{{$loop->index}}">
                                <option>{{$data->A級資材}}</option>
                                <option>{!! __('basicInfoLang.yes') !!}</option>
                                <option>{!! __('basicInfoLang.no') !!}</option>
                            </select>
                        </td>
                        <td>
                            <select style="width:100px" class="form-select form-select-lg " id="month{{$loop->index}}"
                                name="month{{$loop->index}}">
                                <option>{{$data->月請購}}</option>
                                <option>{!! __('basicInfoLang.yes') !!}</option>
                                <option>{!! __('basicInfoLang.no') !!}</option>
                            </select>
                        </td>
                        <td>
                            <select style="width:150px" class="form-select form-select-lg " id="send{{$loop->index}}"
                                name="send{{$loop->index}}">
                                <option>{{$data -> 發料部門}}</option>
                                @foreach ($sends as $send)
                                <option>{{$send -> 發料部門}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select style="width:100px" class="form-select form-select-lg " id="belong{{$loop->index}}"
                                name="belong{{$loop->index}}">
                                <option>{{$data->耗材歸屬}}</option>
                                <option>{!! __('basicInfoLang.consume') !!}</option>
                                <option>{!! __('basicInfoLang.stand') !!}</option>
                            </select>
                        </td>
                        <td><input style="width:80px" type="number" id="price{{$loop->index}}"
                                name="price{{$loop->index}}" value="{{$data->單價}}" step="0.00001"></td>
                        <td>
                            <select style="width:100px" class="form-select form-select-lg " id="money{{$loop->index}}"
                                name="money{{$loop->index}}">
                                <option>{{$data->幣別}}</option>
                                <option>RMB</option>
                                <option>USD</option>
                                <option>JPY</option>
                                <option>TWD</option>
                                <option>VND</option>
                                <option>IDR</option>
                            </select>
                        </td>
                        <td><input style="width:100px" type="text" id="unit{{$loop->index}}" name="unit{{$loop->index}}"
                                value="{{$data->單位}}"></td>
                        <td><input style="width:100px" type="number" id="mpq{{$loop->index}}" name="mpq{{$loop->index}}"
                                value="{{$data->MPQ}}"></td>
                        <td><input style="width:100px" type="number" id="moq{{$loop->index}}" name="moq{{$loop->index}}"
                                value="{{$data->MOQ}}"></td>
                        <td><input style="width:100px" type="number" id="lt{{$loop->index}}" name="lt{{$loop->index}}"
                                value="{{$data->LT}}"></td>
                        <td><input style="width:100px" type="number" id="safe{{$loop->index}}"
                                name="safe{{$loop->index}}" value="{{$data->安全庫存}}"></td>
                        <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    </tr>
                    @endforeach
                </table>
            </div>
            @foreach ($data1 as $data )
            <input type="hidden" id="data0{{$loop->index}}" name="data0{{$loop->index}}" value="{{$data->料號}}">
            <input type="hidden" id="data1{{$loop->index}}" name="data1{{$loop->index}}" value="{{$data->品名}}">
            <input type="hidden" id="data2{{$loop->index}}" name="data2{{$loop->index}}" value="{{$data->規格}}">
            <input type="hidden" id="data3{{$loop->index}}" name="data3{{$loop->index}}" value="{{$data->A級資材}}">
            <input type="hidden" id="data4{{$loop->index}}" name="data4{{$loop->index}}" value="{{$data->月請購}}">
            <input type="hidden" id="data5{{$loop->index}}" name="data5{{$loop->index}}" value="{{$data->發料部門}}">
            <input type="hidden" id="data6{{$loop->index}}" name="data6{{$loop->index}}" value="{{$data->耗材歸屬}}">
            <input type="hidden" id="data7{{$loop->index}}" name="data7{{$loop->index}}" value="{{$data->單價}}">
            <input type="hidden" id="data8{{$loop->index}}" name="data8{{$loop->index}}" value="{{$data->幣別}}">
            <input type="hidden" id="data9{{$loop->index}}" name="data9{{$loop->index}}" value="{{$data->單位}}">
            <input type="hidden" id="data10{{$loop->index}}" name="data10{{$loop->index}}" value="{{$data->MPQ}}">
            <input type="hidden" id="data11{{$loop->index}}" name="data11{{$loop->index}}" value="{{$data->MOQ}}">
            <input type="hidden" id="data12{{$loop->index}}" name="data12{{$loop->index}}" value="{{$data->LT}}">
            <input type="hidden" id="data13{{$loop->index}}" name="data13{{$loop->index}}" value="{{$data->安全庫存}}">
            @endforeach

            <br>
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.delete') !!}">
            <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.change') !!}">
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.download') !!}">
        </form>
        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.material')}}'">{!!
            __('basicInfoLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
