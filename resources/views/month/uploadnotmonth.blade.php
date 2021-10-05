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
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>

            <div class="card-body">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadnotmonth') }}">
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

                        <form  action = "{{ route('month.insertuploadnotmonth') }}"method="POST">
                            @csrf
                            <div class="table-responsive">
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "SXB單號">{!! __('monthlyPRpageLang.sxb') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title3" name = "title3" value = "品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title4" name = "title4" value = "單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                    <th><input type = "hidden" id = "title5" name = "title5" value = "月請購">{!! __('templateWords.monthly') !!}</th>
                                    <th><input type = "hidden" id = "title6" name = "title6" value = "請購數量">{!! __('monthlyPRpageLang.buyamount1') !!}</th>
                                    <th><input type = "hidden" id = "title7" name = "title7" value = "說明">{!! __('monthlyPRpageLang.description') !!}</th>
                                    <th><input type = "hidden" id = "title8" name = "title8" value = "管控項次">{!! __('monthlyPRpageLang.control') !!}</th>

                                    <input type = "hidden" id = "time" name = "time" value = "9">
                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <?php
                                            $name = DB::table('consumptive_material')->where('料號',$row[2])->value('品名');
                                            $unit = DB::table('consumptive_material')->where('料號',$row[2])->value('單位');
                                            $month = DB::table('consumptive_material')->where('料號',$row[2])->value('月請購');

                                            $clients = DB::table('客戶別')->pluck('客戶')->toArray();

                                            $i = false;
                                            $error = $loop->index +1;

                                            //判斷是否有料號
                                            if($name === null || $unit === null)
                                            {
                                                $mess = trans('monthlyPRpageLang.noisn').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[2];
                                                    echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('$mess');
                                                    window.location.href='uploadnotmonth';
                                                    </script>");
                                            }
                                            //判斷是否有這個客戶
                                            if(in_array($row[1],$clients)) $i = true;

                                            if($i === false)
                                            {
                                                $mess = trans('monthlyPRpageLang.noclient').' '.trans('monthlyPRpageLang.row').' : '.$error.' '.$row[1];
                                                    echo ("<script LANGUAGE='JavaScript'>
                                                    window.alert('$mess');
                                                    window.location.href='uploadnotmonth';
                                                    </script>");
                                            }

                                        ?>
                                        <td><input type = "text" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$row[0]}}" required></td>
                                        <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$row[1]}}">{{$row[1]}}</td>
                                        <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$row[2]}}">{{$row[2]}}</td>
                                        <td>{{$name}}</td>
                                        <td>{{$unit}}</td>
                                        <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$month}}">{{$month}}</td>
                                        <td><input type = "number" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$row[3]}}" required></td>
                                        <td><input type = "text" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$row[4]}}" ></td>
                                        <td>
                                            <select style = "width: 150px;" class ="form-control form-control-lg " id = "data5{{$loop->index}}" name="data5{{$loop->index}}">
                                            <option></option>
                                            <option>品質問題</option>
                                            <option>MPS上升</option>
                                            <option>其他</option>
                                            </select>
                                        </td>

                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                        </div>
                        <br>
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                        </form>
                    <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
