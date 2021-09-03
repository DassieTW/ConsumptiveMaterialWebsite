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
                <h3>{!! __('inboundpageLang.stockupload') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('inbound.uploadinventory') }}">
                            @csrf
                            <div class="col-6 ">
                                <label>{!! __('inboundpageLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('inbound.insertuploadinventory') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('inboundpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('inboundpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "品名">{!! __('inboundpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "規格">{!! __('inboundpageLang.format') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "數量">{!! __('inboundpageLang.amount') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title3" value = "儲位">{!! __('inboundpageLang.loc') !!}</th>
                                </tr>
                                @foreach($data as $row)
                                <tr>
                                    <?php
                                        $name = DB::table('consumptive_material')->where('料號',$row[1])->value('品名');
                                        $format = DB::table('consumptive_material')->where('料號',$row[1])->value('規格');
                                        $position = DB::table('儲位')->pluck('儲存位置');
                                        $clients = DB::table('客戶別')->pluck('客戶')->toArray();
                                        $positions = DB::table('儲位')->pluck('儲存位置')->toArray();
                                        $i = false;
                                        $j = false;
                                        $error = $loop->index +1;
                                        //判斷是否有料號
                                        if($name === null || $format === null)
                                        {
                                            echo ("<script LANGUAGE='JavaScript'>
                                            window.alert('Material is not found In Row, +'$error'+'$row[1]'+,Please check Material number');
                                            window.location.href = 'uploadinventory';
                                            </script>");
                                        }
                                        //判斷是否有這個客戶
                                        if(in_array($row[0],$clients)) $i = true;

                                        if($i === false)
                                        {
                                            echo ("<script LANGUAGE='JavaScript'>
                                                window.alert('Format Error,Not Found In Row' +'$error'+ '$row[0] ' + 'in 客戶別');
                                                window.location.href = 'uploadinventory';
                                                </script>");
                                        }

                                        //判斷是否有這個儲位
                                        if(in_array($row[3],$positions)) $j = true;

                                        if($j === false)
                                        {
                                            echo ("<script LANGUAGE='JavaScript'>
                                                window.alert('Format Error,Not Found In Row' + '$error'+'$row[3] ' + 'in 儲位');
                                                window.location.href = 'uploadinventory';
                                                </script>");
                                        }
                                    ?>
                                    <td><input type = "hidden"  name = "data0{{$loop->index}}" value = "{{$row[0]}}">{{$row[0]}}</td>
                                    <td><input type = "hidden"  name = "data1{{$loop->index}}" value = "{{$row[1]}}">{{$row[1]}}</td>
                                    <td>{{$name}}</td>
                                    <td>{{$format}}</td>
                                    <td><input type = "number"  name = "data2{{$loop->index}}" value = "{{$row[2]}}"></td>
                                    <td>
                                    <select class="form-control form-control-lg" id = "data3{{$loop->index}}" name="data3{{$loop->index}}" >
                                    <option style="display: none" selected value = "{{$row[3]}}">{{$row[3]}}</option>
                                    @foreach($positions as $position)
                                    <option>{{  $position }}</option>
                                    @endforeach
                                    </select>
                                    </td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.upload')}}'">{!! __('inboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
