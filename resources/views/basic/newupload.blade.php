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
        <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.newMats') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form method="post" enctype="multipart/form-data" action = "{{ route('basic.uploadmaterial') }}">
                            @csrf
                            <div class="col-6 col-sm-3">
                                <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('basic.insertuploadmaterial') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "料號">{!! __('basicInfoLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "品名">{!! __('basicInfoLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "規格">{!! __('basicInfoLang.format') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title3" value = "單價">{!! __('basicInfoLang.price') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title4" value = "幣別">{!! __('basicInfoLang.money') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title5" value = "單位">{!! __('basicInfoLang.unit') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title6" value = "MPQ">{!! __('basicInfoLang.mpq') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title7" value = "MOQ">{!! __('basicInfoLang.moq') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title8" value = "LT">{!! __('basicInfoLang.lt') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title9" value = "月請購">{!! __('basicInfoLang.month') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title10" value = "A級資材">{!! __('basicInfoLang.gradea') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title11" value = "耗材歸屬">{!! __('basicInfoLang.belong') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title12" value = "發料部門">{!! __('basicInfoLang.senddep') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title13" value = "安全庫存">{!! __('basicInfoLang.safe') !!}</th>
                                    <input type = "hidden" id = "time" name = "time" value = "14">
                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <td><input type = "text"  name = "data0a{{$loop->index}}" value = "{{$row[0]}}" required></td>
                                        <td><input type = "text"  name = "data1a{{$loop->index}}" value = "{{$row[1]}}" required></td>
                                        <td><input type = "text"  name = "data2a{{$loop->index}}" value = "{{$row[2]}}" required></td>
                                        <td><input style="width:70px" type = "number"  name = "data3a{{$loop->index}}" value = "{{$row[3]}}" step="0.00001" required></td>
                                        <td>
                                            <select class="form-control form-control-lg " id = "data4a{{$loop->index}}" name="data4a{{$loop->index}}" required>
                                            <option style="display: none" selected value = "{{$row[4]}}">{{$row[4]}}</option>
                                            <option>RMB</option>
                                            <option>USD</option>
                                            <option>TWD</option>
                                            <option>VND</option>
                                            <option>JPY</option>
                                            </select>
                                        </td>
                                        <td><input style="width:50px" type = "text"  name = "data5a{{$loop->index}}" value = "{{$row[5]}}" required></td>
                                        <td><input style="width:50px" type = "number"  name = "data6a{{$loop->index}}" value = "{{$row[6]}}" required></td>
                                        <td><input style="width:50px" type = "number"  name = "data7a{{$loop->index}}" value = "{{$row[7]}}" required></td>
                                        <td><input style="width:50px" type = "number"  name = "data8a{{$loop->index}}"  step = "0.00001" value = "{{$row[8]}}" required></td>
                                        <td>
                                            <select class="form-control form-control-lg " id = "data9a{{$loop->index}}" name="data9a{{$loop->index}}" value = "{{$row[9]}}" required>
                                            <option style="display: none"  selected value = "{{$row[9]}}">{{$row[9]}}</option>
                                            <option>是</option>
                                            <option>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg " name="data10a{{$loop->index}}" value = "{{$row[10]}}" required>
                                            <option style="display: none"  selected value = "{{$row[10]}}">{{$row[10]}}</option>
                                            <option>是</option>
                                            <option>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg "  name="data11a{{$loop->index}}" value = "{{$row[11]}}" required>
                                            <option style="display: none"  selected value = "{{$row[11]}}">{{$row[11]}}</option>
                                            <option>單耗</option>
                                            <option>站位</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg " name="data12a{{$loop->index}}" value = "{{$row[12]}}" required>
                                            <option style="display: none"  selected value = "{{$row[12]}}">{{$row[12]}}</option>
                                            <option>IE備品室</option>
                                            <option>ME備品室</option>
                                            <option>設備備品室</option>
                                            <option>備品室</option>
                                            </select>
                                        </td>
                                        <td><input style="width:70px" type = "number"  name = "data13a{{$loop->index}}" value = "{{$row[13]}}"></td>

                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.add') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">{!! __('basicInfoLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
