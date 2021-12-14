@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<script src="{{ asset('js/basic/uploadnew.js') }}"></script>
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
<div class="row justify-content-center">
    <div class="card">
        <div class="card-header">
            <h3>{!! __('basicInfoLang.newMats') !!}</h3>
        </div>
        <div class="card w-100">

            <form id="uploadnew" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('basicInfoLang.isn')
                                !!}
                            </th>
                            <th><input type="hidden" id="title1" name="title1" value="品名">{!! __('basicInfoLang.pName')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title2" value="規格">{!! __('basicInfoLang.format')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title3" value="單價">{!! __('basicInfoLang.price')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title4" value="幣別">{!! __('basicInfoLang.money')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title5" value="單位">{!! __('basicInfoLang.unit')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title6" value="MPQ">{!! __('basicInfoLang.mpq')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title7" value="MOQ">{!! __('basicInfoLang.moq')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title8" value="LT">{!! __('basicInfoLang.lt') !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title9" value="月請購">{!! __('basicInfoLang.month')
                                !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title10" value="A級資材">{!!
                                __('basicInfoLang.gradea')
                                !!}</th>
                            <th><input type="hidden" id="title2" name="title11" value="耗材歸屬">{!!
                                __('basicInfoLang.belong')
                                !!}</th>
                            <th><input type="hidden" id="title2" name="title12" value="發料部門">{!!
                                __('basicInfoLang.senddep')
                                !!}</th>
                            <th><input type="hidden" id="title2" name="title13" value="安全庫存">{!!
                                __('basicInfoLang.safe')
                                !!}</th>
                            <input type="hidden" id="time" name="time" value="14">
                        </tr>
                        @foreach($data as $row)
                        <tr>
                            <td><input class="form-control form-control-lg" type="text" id="data0a{{$loop->index}}"
                                    name="data0a{{$loop->index}}" value="{{$row[0]}}" required
                                    oninput="if(value.length>12) value=value.slice(0,12)" style="width:150px"></td>
                            <td><input  style="width:100px" class="form-control form-control-lg" type="text" id="data1a{{$loop->index}}" name="data1a{{$loop->index}}"
                                    value="{{$row[1]}}" required></td>
                            <td><input  style="width:200px" class="form-control form-control-lg" type="text" id="data2a{{$loop->index}}" name="data2a{{$loop->index}}"
                                    value="{{$row[2]}}" required></td>
                            <td><input  class="form-control form-control-lg" style="width:100px" type="number" id="data3a{{$loop->index}}"
                                    name="data3a{{$loop->index}}" value="{{$row[3]}}" step="0.00001" required></td>
                            <td>
                                <select style="width:100px" class="form-select form-select-lg "
                                    id="data4a{{$loop->index}}" id="data4a{{$loop->index}}"
                                    name="data4a{{$loop->index}}" required>
                                    <option style="display: none" selected value="{{$row[4]}}">{{$row[4]}}</option>
                                    <option>RMB</option>
                                    <option>USD</option>
                                    <option>TWD</option>
                                    <option>VND</option>
                                    <option>JPY</option>
                                    <option>IDR</option>
                                </select>
                            </td>
                            <td><input class="form-control form-control-lg" style="width:100px" type="text" id="data5a{{$loop->index}}"
                                    name="data5a{{$loop->index}}" value="{{$row[5]}}" required></td>
                            <td><input class="form-control form-control-lg" style="width:100px" type="number" id="data6a{{$loop->index}}"
                                    name="data6a{{$loop->index}}" value="{{$row[6]}}" required></td>
                            <td><input class="form-control form-control-lg" style="width:100px" type="number" id="data7a{{$loop->index}}"
                                    name="data7a{{$loop->index}}" value="{{$row[7]}}" required></td>
                            <td><input class="form-control form-control-lg" style="width:100px" type="number" id="data8a{{$loop->index}}"
                                    name="data8a{{$loop->index}}" step="0.00001" value="{{$row[8]}}" required></td>
                            <td>
                                <select style="width:100px" class="form-select form-select-lg "
                                    id="data9a{{$loop->index}}" id="data9a{{$loop->index}}"
                                    name="data9a{{$loop->index}}" value="{{$row[9]}}" required>
                                    <option style="display: none" selected value="{{$row[9]}}">{{$row[9]}}</option>
                                    <option>{!! __('basicInfoLang.yes') !!}</option>
                                    <option>{!! __('basicInfoLang.no') !!}</option>
                                </select>
                            </td>

                            <td>
                                <select style="width:100px" class="form-select form-select-lg "
                                    id="data10a{{$loop->index}}" name="data10a{{$loop->index}}" value="{{$row[10]}}"
                                    required>
                                    <option style="display: none" selected value="{{$row[10]}}">{{$row[10]}}</option>
                                    <option>{!! __('basicInfoLang.yes') !!}</option>
                                    <option>{!! __('basicInfoLang.no') !!}</option>
                                </select>
                            </td>
                            <td>
                                <select style="width:100px" class="form-select form-select-lg "
                                    id="data11a{{$loop->index}}" name="data11a{{$loop->index}}" value="{{$row[11]}}"
                                    required>
                                    <option style="display: none" selected value="{{$row[11]}}">{{$row[11]}}</option>
                                    <option>{!! __('basicInfoLang.consume') !!}</option>
                                    <option>{!! __('basicInfoLang.stand') !!}</option>
                                </select>
                            </td>
                            <td>
                                <select style="width:150px" class="form-select form-select-lg "
                                    id="data12a{{$loop->index}}" name="data12a{{$loop->index}}" value="{{$row[12]}}"
                                    required>
                                    <option style="display: none" selected value="{{$row[12]}}">{{$row[12]}}</option>
                                    @foreach ($senddata as $send)
                                    <option>{{$send->發料部門}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input class="form-control form-control-lg " style="width:100px" type="number"
                                    id="data13a{{$loop->index}}" name="data13a{{$loop->index}}" value="{{$row[13]}}">
                            </td>

                        </tr>
                        <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                        @endforeach

                    </table>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.add') !!}">
            </form>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="row w-100 justify-content-start">
                <div class="col col-auto">
                    <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">{!!
                        __('basicInfoLang.return') !!}</button>
                </div>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
