@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/picklist.js') }}"></script>
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
        <h3>{!! __('outboundpageLang.picklist') !!}</h3>
    </div>
    <div class="card-body">
        <form id="picklist">
            @csrf
            <div class="table-responsive">

                <select class=" form-select form-select-lg " id="list" name="list" required width="250"
                    style="width: 250px">
                    <option style="display: none" disabled selected value="">{!! __('outboundpageLang.searchpicklist')
                        !!}</option>
                    @foreach($number as $number)
                    <option>{{ $number->領料單號 }}</option>
                    @endforeach
                </select>

                <table class="table" id="test">
                    <tr id="require">
                        <th>{!! __('outboundpageLang.client') !!}</th>
                        <th>{!! __('outboundpageLang.machine') !!}</th>
                        <th>{!! __('outboundpageLang.process') !!}</th>
                        <th>{!! __('outboundpageLang.usereason') !!}</th>
                        <th>{!! __('outboundpageLang.line') !!}</th>
                        <th>{!! __('outboundpageLang.isn') !!}</th>
                        <th>{!! __('outboundpageLang.pName') !!}</th>
                        <th>{!! __('outboundpageLang.format') !!}</th>
                        <th>{!! __('outboundpageLang.unit') !!}</th>
                        <th>{!! __('outboundpageLang.pickamount') !!}</th>
                        <th>{!! __('outboundpageLang.realpickamount') !!}</th>
                        <th>{!! __('outboundpageLang.mark') !!}</th>
                        <th>{!! __('outboundpageLang.diffreason') !!}</th>
                        <th>{!! __('outboundpageLang.picklistnum') !!}</th>
                        <th>{!! __('outboundpageLang.opentime') !!}</th>
                        <th>{!! __('outboundpageLang.loc') !!}</th>
                    </tr>
                    </tr>
                    @foreach($data as $data)
                    <tr id="{{$data->領料單號}}">
                        <?php
                                            $stock = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('現有庫存')->toArray();
                                            $position = DB::table('inventory')->where('料號',$data->料號)->where('客戶別',$data->客戶別)->pluck('儲位')->toArray();
                                            $test = array_combine($position, $stock);
                                        ?>

                        <td id="client{{$data->領料單號}}">{{$data->客戶別}}</td>
                        <td>{{$data->機種}}</td>
                        <td>{{$data->製程}}</td>
                        <td>{{$data->領用原因}}</td>
                        <td>{{$data->線別}}</td>
                        <td id="number{{$data->領料單號}}">{{$data->料號}}</td>
                        <td>{{$data->品名}}</td>
                        <td>{{$data->規格}}</td>
                        <td>{{$data->單位}}</td>
                        <td id="advance{{$data->領料單號}}">{{$data->預領數量}}</td>
                        <td><input style="width:120px" type="number" id="amount{{$data->領料單號}}"
                                name="amount{{$data->領料單號}}" required value="{{$data->實際領用數量}}"></td>
                        <td>{{$data->備註}}</td>
                        <td><input style="width:100px" type="text" id="reason{{$data->領料單號}}"
                                name="reason{{$data->領料單號}}" value="{{$data->實領差異原因}}"></td>
                        <td>{{$data->領料單號}}</td>
                        <td>{{$data->開單時間}}</td>
                        <td>
                            <select style="width: 120px" class="form-select form-select-lg"
                                name="position{{$data->領料單號}}" id="position{{$data->領料單號}}">
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.enterloc') !!}</option>
                                @foreach ($test as $k=> $a)
                                <option>儲位:{{$k}} 現有庫存:{{$a}}</option>
                                @endforeach
                        </td>
                    </tr>

                    @endforeach

                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div id="reasonerror" style="display:none;">
                <h3 style="color: red">{!! __('outboundpageLang.enterdiffreason') !!}</h3>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('outboundpageLang.sendpeople') !!}</label>
            <select class="form-select form-select-lg" id="sendpeople" name="sendpeople" required width="250"
                style="width: 250px">
                <option style="display: none" disabled selected value="">{!! __('outboundpageLang.entersendpeople') !!}
                </option>
                @foreach($people as $people)
                <option>{{ $people->工號 .' '. $people->姓名 }}</option>
                @endforeach
            </select>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('outboundpageLang.pickpeople') !!}</label>
            <select class="form-select form-select-lg" id="pickpeople" name="pickpeople" required width="250"
                style="width: 250px">
                <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterpickpeople') !!}
                </option>
                @foreach($people1 as $people)
                <option>{{ $people->工號 .' '. $people->姓名 }}</option>
                @endforeach
            </select>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div id="lessstock" style="display:none;">
                <h3 style="color: red" id="position"></h3>
                <h3 style="color: red" id="nowstock"></h3>
                <h3 style="color: red" id="amount"></h3>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('outboundpageLang.submit') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.picklistpage')}}'">{!!
            __('outboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
