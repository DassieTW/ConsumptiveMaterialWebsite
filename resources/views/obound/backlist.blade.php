@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/backlist.js') }}"></script>
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
                <h3>{!! __('oboundpageLang.backlist') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "backlist">
                    @csrf

                            <select class=" form-control form-control-lg " id = "list" name="list"  required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.searchbacklist') !!}</option>
                            @foreach($number as $number)
                            <option>{{  $number->退料單號 }}</option>
                            @endforeach
                            </select>
                            <div class="table-responsive">
                            <table class="table" id = "test">
                                <tr id = "require">
                                    <th>{!! __('oboundpageLang.client') !!}</th>
                                    <th>{!! __('oboundpageLang.machine') !!}</th>
                                    <th>{!! __('oboundpageLang.process') !!}</th>
                                    <th>{!! __('oboundpageLang.backreason') !!}</th>
                                    <th>{!! __('oboundpageLang.line') !!}</th>
                                    <th>{!! __('oboundpageLang.isn') !!}</th>
                                    <th>{!! __('oboundpageLang.pName') !!}</th>
                                    <th>{!! __('oboundpageLang.format') !!}</th>
                                    <th>{!! __('oboundpageLang.backamount') !!}</th>
                                    <th>{!! __('oboundpageLang.realbackamount') !!}</th>
                                    <th>{!! __('oboundpageLang.mark') !!}</th>
                                    <th>{!! __('oboundpageLang.backdiffreason') !!}</th>
                                    <th>{!! __('oboundpageLang.backlistnum') !!}</th>
                                    <th>{!! __('oboundpageLang.opentime') !!}</th>
                                </tr>
                                    @foreach($data as $data)
                                    <tr id = "{{$data->退料單號}}">
                                        <td id = "client{{$data->退料單號}}">{{$data->客戶別}}</td>
                                        <td>{{$data->機種}}</td>
                                        <td>{{$data->制程}}</td>
                                        <td>{{$data->退回原因}}</td>
                                        <td>{{$data->線別}}</td>
                                        <td id = "number{{$data->退料單號}}">{{$data->料號}}</td>
                                        <td>{{$data->品名}}</td>
                                        <td>{{$data->規格}}</td>
                                        <td id = "advance{{$data->退料單號}}">{{$data->預退數量}}</td>
                                        <td><input type="number" id ="amount{{$data->退料單號}}" name="amount{{$data->退料單號}}" required value = "{{$data->實際退回數量}}"></td>
                                        <td>{{$data->備註}}</td>
                                        <td><input type="text" id ="reason{{$data->退料單號}}" name="reason{{$data->退料單號}}"  value = "{{$data->實退差異原因}}"></td>
                                        <td>{{$data->退料單號}}</td>
                                        <td>{{$data->開單時間}}</td>
                                    </tr>
                                    @endforeach

                            </table>
                            </div>
                            <br>
                            <div id = "reasonerror" style="display:none; color:red;"><h3 style="color: red">{!! __('oboundpageLang.enterdiffreason') !!}</h3></div>

                            <label class="form-label">{!! __('oboundpageLang.receivepeople') !!}</label>
                            <select class="form-control form-control-lg" id = "pickpeople" name="pickpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterreceivepeople') !!}</option>
                            @foreach($people as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.backpeople') !!}</label>
                            <select class="form-control form-control-lg" id = "backpeople" name="backpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterbackpeople') !!}</option>
                            @foreach($people1 as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>



                            <label class="form-label">{!! __('oboundpageLang.bound') !!}</label>
                            <select class="form-control form-control-lg" id = "bound" name="bound" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterbound') !!}</option>
                            @foreach($bound as $bound)
                            <option>{{  $bound->O庫 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.status') !!}</label>
                            <select class="form-control form-control-lg" id = "status" name="status" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterstatus') !!}</option>
                            <option>{!! __('oboundpageLang.good') !!}</option>
                            <option>{!! __('oboundpageLang.nogood') !!}</option>
                            </select>

                            <br>
                            <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}">
                            </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.backlistpage')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
