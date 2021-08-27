@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.monthly') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadmonth') }}">
                            @csrf
                            <div class="col-6 col-sm-3">
                                <label>{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('month.insertuploadmonth') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th><input type = "hidden" id = "title3" name = "title3" value = "製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                    <th><input type = "hidden" id = "title4" name = "title4" value = "本月MPS">{!! __('monthlyPRpageLang.nowmps') !!}</th>
                                    <th><input type = "hidden" id = "title5" name = "title5" value = "本月生產天數">{!! __('monthlyPRpageLang.nowday') !!}</th>
                                    <th><input type = "hidden" id = "title6" name = "title6" value = "下月MPS">{!! __('monthlyPRpageLang.nextmps') !!}</th>
                                    <th><input type = "hidden" id = "title7" name = "title7" value = "下月生產天數">{!! __('monthlyPRpageLang.nextday') !!}</th>

                                    <input type = "hidden" id = "time" name = "time" value = "7">
                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <?php

                                            $clients = DB::table('客戶別')->pluck('客戶')->toArray();
                                            $machines = DB::table('機種')->pluck('機種')->toArray();
                                            $productions = DB::table('製程')->pluck('製程')->toArray();

                                            $i = false;
                                            $j = false;
                                            $k = false;

                                            //判斷是否有這個客戶
                                            if(in_array($row[0],$clients)) $i = true;

                                            if($i === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[0] ' + 'in 客戶別');
                                                    window.location.href = 'uploadmonth';
                                                    </script>");
                                            }

                                            //判斷是否有這個機種
                                            if(in_array($row[1],$machines)) $j = true;

                                            if($j === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[1] ' + 'in 機種');
                                                    window.location.href = 'uploadmonth';
                                                    </script>");
                                            }

                                            //判斷是否有這個製程
                                            if(in_array($row[2],$productions)) $k = true;

                                            if($k === false)
                                            {
                                                echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('Format Error,Not Found ' + '$row[2] ' + 'in 製程');
                                                    window.location.href = 'uploadmonth';
                                                    </script>");
                                            }

                                        ?>
                                        <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$row[0]}}">{{$row[0]}}</td>
                                        <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$row[1]}}">{{$row[1]}}</td>
                                        <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$row[2]}}">{{$row[2]}}</td>
                                        <td><input type = "number" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$row[3]}}" required></td>
                                        <td><input type = "number" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$row[4]}}" required></td>
                                        <td><input type = "number" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$row[5]}}" required></td>
                                        <td><input type = "number" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$row[6]}}" required></td>



                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importmonth')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
