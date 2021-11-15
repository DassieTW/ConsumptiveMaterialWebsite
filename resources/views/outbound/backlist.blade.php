@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<link rel="stylesheet" href="{{ asset('css/outbound/backlist.css') }}">
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
        <form id="backlist">
            @csrf
            <div class="table-responsive">

                <select class=" form-select form-select-lg " id="list" name="list" required width="250"
                    style="width: 250px">
                    <option style="display: none" disabled selected value="">{!! __('outboundpageLang.searchbacklist')
                        !!}</option>
                    @foreach($number as $number)
                    <option>{{ $number->退料單號 }}</option>
                    @endforeach
                </select>

                <table class="table" id="test">
                    <tr id="require">
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
                    <tr id="{{$data->退料單號}}">
                        <td id="client{{$data->退料單號}}">{{$data->客戶別}}</td>
                        <td>{{$data->機種}}</td>
                        <td>{{$data->製程}}</td>
                        <td>{{$data->退回原因}}</td>
                        <td>{{$data->線別}}</td>
                        <td id="number{{$data->退料單號}}">{{$data->料號}}</td>
                        <td>{{$data->品名}}</td>
                        <td>{{$data->規格}}</td>
                        <td>{{$data->單位}}</td>
                        <td id="advance{{$data->退料單號}}">{{$data->預退數量}}</td>
                        <td><input type="number" id="amount{{$data->退料單號}}" name="amount{{$data->退料單號}}" required
                                value="{{$data->實際退回數量}}"></td>
                        <td>{{$data->備註}}</td>
                        <td><input type="text" id="reason{{$data->退料單號}}" name="reason{{$data->退料單號}}"
                                value="{{$data->實退差異原因}}"></td>
                        <td>{{$data->退料單號}}</td>
                        <td>{{$data->開單時間}}</td>
                    </tr>
                    @endforeach

                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div id="reasonerror" style="display:none;">
                <h3 style="color: red">{!! __('outboundpageLang.enterdiffreason') !!}</h3>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('outboundpageLang.receivepeople') !!}</label>
            <input class="form-control form-control-lg" id="pickpeople" name="pickpeople" required width="250"
                style="width: 250px" placeholder="{!! __('outboundpageLang.inputreceivepeople') !!}"
                oninput="myFunction()">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <ul id="receivemenu">
                @foreach($people as $people)
                <li class="receiveli" style="display: none;"><a href="#">{{ $people->工號 .' '. $people->姓名 }}</a></li>
                @endforeach
            </ul>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('outboundpageLang.backpeople') !!}</label>
            <input class="form-control form-control-lg" id="backpeople" name="backpeople" required width="250"
                style="width: 250px" placeholder="{!! __('outboundpageLang.inputbackpeople') !!}"
                oninput="myFunction2()">
            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterbackpeople')
                !!}</option>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <ul id="backmenu">
                @foreach($people1 as $people)
                <li class="backli" style="display: none;"><a href="#">{{ $people->工號 .' '. $people->姓名 }}</a></li>
                @endforeach
            </ul>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            @foreach($check as $people)
            <input type="hidden" id="checkpeople{{$loop->index}}" name="checkpeople{{$loop->index}}"
                value="{{$people->工號}}">
            <input type="hidden" id="count" name="count" value="{{$loop->count}}">
            @endforeach

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('outboundpageLang.loc') !!}</label>
            <select class="form-select form-select-lg" id="position" name="position" required width="250"
                style="width: 250px">
                <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterloc') !!}
                </option>
                @foreach($position as $position)
                <option>{{ $position->儲存位置 }}</option>
                @endforeach
            </select>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('outboundpageLang.status') !!}</label>
            <select class="form-select form-select-lg" id="status" name="status" required width="250"
                style="width: 250px">
                <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterstatus') !!}
                </option>
                <option>{!! __('outboundpageLang.good') !!}</option>
                <option>{!! __('outboundpageLang.nogood') !!}</option>
            </select>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('outboundpageLang.submit') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.backlistpage')}}'">{!!
            __('outboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
