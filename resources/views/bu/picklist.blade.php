@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/bu/picklist.js') }}"></script>
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
                <h3>{!! __('bupagelang.picklist') !!}</h3>
            </div>
            <div class="card-body">
                <form  id = "picklist" methood = "POST">
                    @csrf
                <div class="table-responsive">

                        <table class="table" >
                            <tr id = "require">
                                <th>{!! __('bupagelang.dblist') !!}</th>
                                <th>{!! __('bupagelang.isn') !!}</th>
                                <th>{!! __('bupagelang.pName') !!}</th>
                                <th>{!! __('bupagelang.format') !!}</th>
                                <th>{!! __('bupagelang.realamount') !!}</th>
                                <th>{!! __('bupagelang.realpickamount') !!}</th>
                                <th>{!! __('bupagelang.outfactory') !!}</th>
                                <th>{!! __('bupagelang.receivefac') !!}</th>
                                <th>{!! __('bupagelang.remark') !!}</th>
                                <th>{!! __('bupagelang.loc') !!}</th>
                                <th>{!! __('bupagelang.client') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <tr id = "{{$data->調撥單號}}">
                                    <?php
                                        $database = $data->接收廠區;
                                        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
                                        \DB::purge(env("DB_CONNECTION"));

                                        $client = DB::table('inventory')->where('料號',$data->料號)->where('現有庫存','>',0)->pluck('客戶別')->toArray();
                                        $nowstock = DB::table('inventory')->where('料號',$data->料號)->where('現有庫存','>',0)->pluck('現有庫存')->toArray();
                                        $position = DB::table('inventory')->where('料號',$data->料號)->where('現有庫存','>',0)->pluck('儲位')->toArray();

                                        $loc = DB::table('儲位')->pluck('儲存位置')->toArray();
                                        $customer = DB::table('客戶別')->pluck('客戶')->toArray();
                                        $sure = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                                        $jobnumber = DB::table('人員信息')->pluck('工號')->toArray();
                                        $name = DB::table('人員信息')->pluck('姓名')->toArray();
                                        $test = array_combine($jobnumber, $name);
                                        $count = count($client);
                                        for ($x = 0; $x < $count; $x++)
                                        {
                                            $keys[] = 'u'.$x;
                                        }
                                        $result = array_merge_recursive(
                                                    array_combine($keys, $client),
                                                    array_combine($keys, $nowstock),
                                                    array_combine($keys, $position)
                                                );

                                    ?>

                                    <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->調撥單號}}">{{$data->調撥單號}}</td>
                                    <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->撥出數量}}">{{$data->撥出數量}}</td>
                                    <td><input type = "number" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$data->撥出數量}}" required></td>
                                    <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$data->撥出廠區}}">{{$data->撥出廠區}}</td>
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$data->接收廠區}}">{{$data->接收廠區}}</td>
                                    <input type = "hidden" id = "sure" name = "sure" value = "{{$sure}}">
                                    <td>
                                        @foreach($result as $k)
                                        {!! __('bupagelang.client') !!} : {{$k[0]}} {!! __('bupagelang.loc') !!} : {{$k[2]}} {!! __('bupagelang.nowstock') !!} : {{$k[1]}} <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <select class="form-control form-control-lg" id = "position" name="position" required width="100" style="width: 100px">
                                        <option style="display: none" disabled selected value = "">{!! __('bupagelang.enterloc') !!}</option>
                                        @foreach($loc as $x)
                                        <option>{{$x}}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-lg" id = "client" name="client" required width="100" style="width: 100px">
                                        <option style="display: none" disabled selected value = "">{!! __('bupagelang.enterclient') !!}</option>
                                        @foreach($customer as $x)
                                        <option>{{$x}}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                </tr>

                            @endforeach

                        </table>
                </div>


                            <br>

                            <label class="form-label">{!! __('bupagelang.receivepeople') !!}</label>
                            <select class="form-control form-control-lg" id = "pickpeople" name="pickpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('bupagelang.enterreceivepeople') !!}</option>
                            @foreach($test as $k=> $a)
                            <option>{!! __('bupagelang.jobnum') !!} : {{$k}} {!! __('bupagelang.receivepeople') !!} : {{$a}}</option>
                            @endforeach
                            </select>


                            <br>
                            <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('bupagelang.submit') !!}">
                            </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.picklistpage')}}'">{!! __('bupagelang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
