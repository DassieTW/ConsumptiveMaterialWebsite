@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/backlist.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.outbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.backlist') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "backlist">
                    @csrf
                <div class="table-responsive">

                            <select class=" form-control form-control-lg " id = "list" name="list"  required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.searchbacklist') !!}</option>
                            @foreach($number as $number)
                            <option>{{  $number->退料單號 }}</option>
                            @endforeach
                            </select>

                            <table class="table" id = "test">
                                <tr id = "require">
                                    <th>{!! __('outboundpageLang.client') !!}</th>
                                    <th>{!! __('outboundpageLang.machine') !!}</th>
                                    <th>{!! __('outboundpageLang.process') !!}</th>
                                    <th>{!! __('outboundpageLang.backreason') !!}</th>
                                    <th>{!! __('outboundpageLang.line') !!}</th>
                                    <th>{!! __('outboundpageLang.isn') !!}</th>
                                    <th>{!! __('outboundpageLang.pName') !!}</th>
                                    <th>{!! __('outboundpageLang.format') !!}</th>
                                    <th>{!! __('outboundpageLang.unit') !!}</th>
                                    <th>{!! __('outboundpageLang.backamount') !!}</th>
                                    <th>{!! __('outboundpageLang.realbackamount') !!}</th>
                                    <th>{!! __('outboundpageLang.mark') !!}</th>
                                    <th>{!! __('outboundpageLang.backdiffreason') !!}</th>
                                    <th>{!! __('outboundpageLang.backlistnum') !!}</th>
                                    <th>{!! __('outboundpageLang.opentime') !!}</th>
                                </tr>
                                    @foreach($data as $data)
                                    <tr id = "{{$data->退料單號}}">
                                        <td id = "client{{$data->退料單號}}">{{$data->客戶別}}</td>
                                        <td>{{$data->機種}}</td>
                                        <td>{{$data->製程}}</td>
                                        <td>{{$data->退回原因}}</td>
                                        <td>{{$data->線別}}</td>
                                        <td id = "number{{$data->退料單號}}">{{$data->料號}}</td>
                                        <td>{{$data->品名}}</td>
                                        <td>{{$data->規格}}</td>
                                        <td>{{$data->單位}}</td>
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
                            <div id = "reasonerror" style="display:none;"><h3 style="color: red">{!! __('outboundpageLang.enterdiffreason') !!}</h3></div>

                            <br>
                            <label class="form-label">{!! __('outboundpageLang.receivepeople') !!}</label>
                            <select class="form-control form-control-lg" id = "pickpeople" name="pickpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterreceivepeople') !!}</option>
                            @foreach($people as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.backpeople') !!}</label>
                            <select class="form-control form-control-lg" id = "backpeople" name="backpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterbackpeople') !!}</option>
                            @foreach($people1 as $people)
                            <option>{{  $people->工號 .'  '. $people->姓名 }}</option>
                            @endforeach
                            </select>



                            <label class="form-label">{!! __('outboundpageLang.loc') !!}</label>
                            <select class="form-control form-control-lg" id = "position" name="position" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterloc') !!}</option>
                            @foreach($position as $position)
                            <option>{{  $position->儲存位置 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.status') !!}</label>
                            <select class="form-control form-control-lg" id = "status" name="status" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterstatus') !!}</option>
                            <option>{!! __('outboundpageLang.good') !!}</option>
                            <option>{!! __('outboundpageLang.nogood') !!}</option>
                            </select>

                            <br>
                            <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}">
                            </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.backlistpage')}}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
