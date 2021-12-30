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
                    <?php
                        $data->單價 = round($data->單價 , 3);
                        $data->LT = round($data->LT , 3);
                    ?>
                    <tr>
                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                style="width:25px;height:25px;" value="{{$loop->index}}"></td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="name{{$loop->index}}" name="name{{$loop->index}}"
                            value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="format{{$loop->index}}" name="format{{$loop->index}}"
                            value="{{$data->規格}}">{{$data->規格}}</td>
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
                        <td><input style="width:120px" type="number" id="price{{$loop->index}}"
                            class="form-control form-control-lg" name="price{{$loop->index}}" value="{{$data->單價}}" step="0.00001" min="0"></td>
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
                                value="{{$data->單位}}" class="form-control form-control-lg"></td>
                        <td><input style="width:100px" type="number" id="mpq{{$loop->index}}" name="mpq{{$loop->index}}"
                                value="{{$data->MPQ}}" class="form-control form-control-lg" min="0"></td>
                        <td><input style="width:100px" type="number" id="moq{{$loop->index}}" name="moq{{$loop->index}}"
                                value="{{$data->MOQ}}" class="form-control form-control-lg" min="0"></td>
                        <td><input style="width:100px" type="number" id="lt{{$loop->index}}" name="lt{{$loop->index}}"
                                value="{{$data->LT}}" class="form-control form-control-lg" min="0"></td>
                        <td><input class = "form-control form-control-lg"style="width:100px" type="number" id="safe{{$loop->index}}"
                                name="safe{{$loop->index}}" value="{{$data->安全庫存}}" min="0"></td>
                        <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.delete') !!}">
            <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.change') !!}">
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                value="{!! __('basicInfoLang.download') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.material')}}'">{!!
            __('basicInfoLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
