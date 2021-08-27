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
        <h2>{!! __('templateWords.obound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.stockupload') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('obound.uploadinventory') }}">
                            @csrf
                            <div class="col-6 ">
                                <label>{!! __('oboundpageLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('obound.insertuploadinventory') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "數量">{!! __('oboundpageLang.amount') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title3" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                </tr>
                                @foreach($data as $row)
                                <tr>
                                    <?php
                                        $name = DB::table('O庫_material')->where('料號',$row[1])->value('品名');
                                        $format = DB::table('O庫_material')->where('料號',$row[1])->value('規格');
                                        $clients = DB::table('客戶別')->pluck('客戶')->toArray();
                                        $i = false;
                                        $j = false;
                                        //判斷是否有料號
                                        if($name === null || $format === null)
                                        {
                                            echo ("<script LANGUAGE='JavaScript'>
                                            window.alert('Material is not found , Please check Material number');
                                            window.location.href = 'uploadinventory';
                                            </script>");
                                        }
                                        //判斷是否有這個客戶
                                        if(in_array($row[0],$clients)) $i = true;

                                        if($i === false)
                                        {
                                            echo ("<script LANGUAGE='JavaScript'>
                                                window.alert('Format Error,Not Found ' + '$row[0] ' + 'in 客戶別');
                                                window.location.href = 'uploadinventory';
                                                </script>");
                                        }

                                    ?>
                                    <td><input type = "hidden"  name = "data0{{$loop->index}}" value = "{{$row[0]}}">{{$row[0]}}</td>
                                    <td><input type = "hidden"  name = "data1{{$loop->index}}" value = "{{$row[1]}}">{{$row[1]}}</td>
                                    <td><input type = "hidden"  name = "data4{{$loop->index}}" value = "{{$name}}">{{$name}}</td>
                                    <td><input type = "hidden"  name = "data5{{$loop->index}}" value = "{{$format}}">{{$format}}</td>
                                    <td><input type = "number"  name = "data2{{$loop->index}}" value = "{{$row[2]}}"></td>
                                    <td><input type = "text"  name = "data3{{$loop->index}}" value = "{{$row[3]}}"></td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.upload')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
