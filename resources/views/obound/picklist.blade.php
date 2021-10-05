@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/picklist.js') }}"></script>
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
                <h3>{!! __('oboundpageLang.picklist') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "picklist">
                    @csrf

                        <select class=" form-control form-control-lg " id = "list" name="list"  required width="250" style="width: 250px">
                        <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.searchpicklist') !!}</option>
                        @foreach($number as $number)
                        <option>{{  $number->領料單號 }}</option>
                        @endforeach
                        </select>
                        <div class="table-responsive">
                            <table class="table" id = "test">
                                <tr id = "require">
                                    <th>{!! __('oboundpageLang.client') !!}</th>
                                    <th>{!! __('oboundpageLang.machine') !!}</th>
                                    <th>{!! __('oboundpageLang.process') !!}</th>
                                    <th>{!! __('oboundpageLang.usereason') !!}</th>
                                    <th>{!! __('oboundpageLang.line') !!}</th>
                                    <th>{!! __('oboundpageLang.isn') !!}</th>
                                    <th>{!! __('oboundpageLang.pName') !!}</th>
                                    <th>{!! __('oboundpageLang.format') !!}</th>
                                    <th>{!! __('oboundpageLang.pickamount') !!}</th>
                                    <th>{!! __('oboundpageLang.realpickamount') !!}</th>
                                    <th>{!! __('oboundpageLang.mark') !!}</th>
                                    <th>{!! __('oboundpageLang.diffreason') !!}</th>
                                    <th>{!! __('oboundpageLang.picklistnum') !!}</th>
                                    <th>{!! __('oboundpageLang.opentime') !!}</th>
                                    <th>{!! __('oboundpageLang.bound') !!}</th>
                                </tr>
                                </tr>
                                    @foreach($data as $data)
                                    <tr id = "{{$data->領料單號}}">
                                        <?php
                                            $stock = DB::table('O庫inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->where('現有庫存', '>', 0)->pluck('現有庫存')->toArray();
                                            $position = DB::table('O庫inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->where('現有庫存', '>', 0)->pluck('庫別')->toArray();
                                            $test = array_combine($position, $stock);
                                        ?>

                                        <td id = "client{{$data->領料單號}}">{{$data->客戶別}}</td>
                                        <td>{{$data->機種}}</td>
                                        <td>{{$data->製程}}</td>
                                        <td>{{$data->領用原因}}</td>
                                        <td>{{$data->線別}}</td>
                                        <td id = "number{{$data->領料單號}}">{{$data->料號}}</td>
                                        <td>{{$data->品名}}</td>
                                        <td>{{$data->規格}}</td>
                                        <td id = "advance{{$data->領料單號}}">{{$data->預領數量}}</td>
                                        <td><input style="width:70px" type="number" id ="amount{{$data->領料單號}}" name="amount{{$data->領料單號}}" required value = "{{$data->實際領用數量}}"></td>
                                        <td>{{$data->備註}}</td>
                                        <td><input style="width:100px" type="text" id ="reason{{$data->領料單號}}" name="reason{{$data->領料單號}}"  value = "{{$data->實領差異原因}}"></td>
                                        <td>{{$data->領料單號}}</td>
                                        <td>{{$data->開單時間}}</td>
                                        <td>
                                            <select class="form-control form-control-lg" style = "width: 150px;" name = "bound{{$data->領料單號}}" id = "bound{{$data->領料單號}}">
                                                <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterbound') !!}</option>
                                                @foreach ($test as $k=> $a)
                                                <option>庫別:{{$k}} 現有庫存:{{$a}}</option>
                                                @endforeach
                                        </td>
                                    </tr>

                                    @endforeach

                            </table>
                        </div>
                        <br>
                        <div id = "reasonerror" style="display:none; color:red;"><h3 style="color: red">{!! __('oboundpageLang.enterdiffreason') !!}</h3></div>

                            <label class="form-label">{!! __('oboundpageLang.sendpeople') !!}</label>
                            <select class="form-control form-control-lg" id = "sendpeople" name="sendpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.entersendpeople') !!}</option>
                            @foreach($people as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.pickpeople') !!}</label>
                            <select class="form-control form-control-lg" id = "pickpeople" name="pickpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterpickpeople') !!}</option>
                            @foreach($people1 as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>

                            </select>
                            <div id = "lessstock" style="display:none;">
                                <h3 style="color: red" id = "position"></h3>
                                <h3 style="color: red" id = "nowstock"></h3>
                                <h3 style="color: red" id = "amount"></h3>
                            </div>
                            <br>
                            <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}">
                            </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.picklistpage')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
