@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* for single line table with over-flow , SAP style as asked */
    table {
        /* table-layout: fixed; */
        /* width: 900px; */
    }

    .table-responsive {
        height: 600px;
        overflow: scroll;
    }

    thead tr:nth-child(1) th {
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

</style>
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/download.js') }}"></script>
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
        <h3>{!! __('outboundpageLang.backrecord') !!}</h3>
        <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
    </div>
    <div class="card-body">

        <form id = "backtable" method="POST">
            @csrf
            <input type="hidden" id="titlename" name="titlename" value="退料記錄表">
            <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
            value="{!! __('outboundpageLang.download') !!}">
            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.backrecord')}}'">{!!
                __('outboundpageLang.return') !!}</button> --}}

            <div class="table-responsive text-nowrap">
                <table class="table" id="pickrecordlist">
                    <thead>
                    <tr>

                        <th><input type="hidden" id="title0" name="title0" value="客戶別">{!! __('outboundpageLang.client')
                            !!}</th>
                        <th><input type="hidden" id="title1" name="title1" value="機種">{!! __('outboundpageLang.machine')
                            !!}</th>
                        <th><input type="hidden" id="title2" name="title2" value="製程">{!! __('outboundpageLang.process')
                            !!}</th>
                        <th><input type="hidden" id="title3" name="title3" value="退回原因">{!!
                            __('outboundpageLang.backreason') !!}</th>
                        <th><input type="hidden" id="title4" name="title4" value="線別">{!! __('outboundpageLang.line')
                            !!}</th>
                        <th><input type="hidden" id="title5" name="title5" value="料號">{!! __('outboundpageLang.isn') !!}
                        </th>
                        <th><input type="hidden" id="title6" name="title6" value="品名">{!! __('outboundpageLang.pName')
                            !!}</th>
                        <th><input type="hidden" id="title7" name="title7" value="規格">{!! __('outboundpageLang.format')
                            !!}</th>
                        <th><input type="hidden" id="title8" name="title8" value="單位">{!! __('outboundpageLang.unit')
                            !!}</th>
                        <th><input type="hidden" id="title9" name="title9" value="預退數量">{!!
                            __('outboundpageLang.backamount') !!}</th>
                        <th><input type="hidden" id="title10" name="title10" value="實際退回數量">{!!
                            __('outboundpageLang.realbackamount') !!}</th>
                        <th><input type="hidden" id="title11" name="title11" value="備註">{!! __('outboundpageLang.mark')
                            !!}</th>
                        <th><input type="hidden" id="title12" name="title12" value="實退差異原因">{!!
                            __('outboundpageLang.backdiffreason') !!}</th>
                        <th><input type="hidden" id="title13" name="title13" value="儲位">{!! __('outboundpageLang.loc')
                            !!}</th>
                        <th><input type="hidden" id="title14" name="title14" value="收料人員">{!!
                            __('outboundpageLang.receivepeople') !!}</th>
                        <th><input type="hidden" id="title15" name="title15" value="收料人員工號">{!!
                            __('outboundpageLang.receivepeoplenum') !!}</th>
                        <th><input type="hidden" id="title16" name="title16" value="退料人員">{!!
                            __('outboundpageLang.backpeople') !!}</th>
                        <th><input type="hidden" id="title17" name="title17" value="退料人員工號">{!!
                            __('outboundpageLang.backpeoplenum') !!}</th>
                        <th><input type="hidden" id="title18" name="title18" value="退料單號">{!!
                            __('outboundpageLang.backlistnum') !!}</th>
                        <th><input type="hidden" id="title19" name="title19" value="開單時間">{!!
                            __('outboundpageLang.opentime') !!}</th>
                        <th><input type="hidden" id="title20" name="title20" value="入庫時間">{!!
                            __('outboundpageLang.inboundtime') !!}</th>
                        <th><input type="hidden" id="title21" name="title21" value="功能狀況">{!!
                            __('outboundpageLang.status') !!}</th>
                        <input type="hidden" id="titlecount" name="titlecount" value="22">
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                    <tr id="{{$data->退料單號}}" class="isnRows">
                        <td><input type="hidden" id="dataa{{$loop->index}}" name="dataa{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="datab{{$loop->index}}" name="datab{{$loop->index}}"
                                value="{{$data->機種}}">{{$data->機種}}</td>
                        <td><input type="hidden" id="datac{{$loop->index}}" name="datac{{$loop->index}}"
                                value="{{$data->製程}}">{{$data->製程}}</td>
                        <td><input type="hidden" id="datad{{$loop->index}}" name="datad{{$loop->index}}"
                                value="{{$data->退回原因}}">{{$data->退回原因}}</td>
                        <td><input type="hidden" id="datae{{$loop->index}}" name="datae{{$loop->index}}"
                                value="{{$data->線別}}">{{$data->線別}}</td>
                        <td><input type="hidden" id="dataf{{$loop->index}}" name="dataf{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="datag{{$loop->index}}" name="datag{{$loop->index}}"
                                value="{{$data->品名}}">{{$data->品名}}</td>
                        <td><input type="hidden" id="datah{{$loop->index}}" name="datah{{$loop->index}}"
                                value="{{$data->規格}}">{{$data->規格}}</td>
                        <td><input type="hidden" id="datai{{$loop->index}}" name="datai{{$loop->index}}"
                                value="{{$data->單位}}">{{$data->單位}}</td>
                        <td><input type="hidden" id="dataj{{$loop->index}}" name="dataj{{$loop->index}}"
                                value="{{$data->預退數量}}">{{$data->預退數量}}</td>
                        <td><input type="hidden" id="datak{{$loop->index}}" name="datak{{$loop->index}}"
                                value="{{$data->實際退回數量}}">{{$data->實際退回數量}}</td>
                        <td><input type="hidden" id="datal{{$loop->index}}" name="datal{{$loop->index}}"
                                value="{{$data->備註}}">{{$data->備註}}</td>
                        <td><input type="hidden" id="datam{{$loop->index}}" name="datam{{$loop->index}}"
                                value="{{$data->實退差異原因}}">{{$data->實退差異原因}}</td>
                        <td><input type="hidden" id="datan{{$loop->index}}" name="datan{{$loop->index}}"
                                value="{{$data->儲位}}">{{$data->儲位}}</td>
                        <td><input type="hidden" id="datao{{$loop->index}}" name="datao{{$loop->index}}"
                                value="{{$data->收料人員}}">{{$data->收料人員}}</td>
                        <td><input type="hidden" id="datap{{$loop->index}}" name="datap{{$loop->index}}"
                                value="{{$data->收料人員工號}}">{{$data->收料人員工號}}</td>
                        <td><input type="hidden" id="dataq{{$loop->index}}" name="dataq{{$loop->index}}"
                                value="{{$data->退料人員}}">{{$data->退料人員}}</td>
                        <td><input type="hidden" id="datar{{$loop->index}}" name="datar{{$loop->index}}"
                                value="{{$data->退料人員工號}}">{{$data->退料人員工號}}</td>
                        <td><input type="hidden" id="datas{{$loop->index}}" name="datas{{$loop->index}}"
                                value="{{$data->退料單號}}">{{$data->退料單號}}</td>
                        <td><input type="hidden" id="datat{{$loop->index}}" name="datat{{$loop->index}}"
                                value="{{$data->開單時間}}">{{$data->開單時間}}</td>
                        <td><input type="hidden" id="datau{{$loop->index}}" name="datau{{$loop->index}}"
                                value="{{$data->入庫時間}}">{{$data->入庫時間}}</td>
                        <td><input type="hidden" id="datav{{$loop->index}}" name="datav{{$loop->index}}"
                                value="{{$data->功能狀況}}">{{$data->功能狀況}}</td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach
                </tbody>
                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    </div>
</div>

</html>
@endsection
