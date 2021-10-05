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
                <h3>{!! __('oboundpageLang.backrecord') !!}</h3>
            </div>
            <div class="card-body">

                        <form action="{{ route('obound.download') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "退料記錄表">
                            <div class="table-responsive">
                        <table class="table" id = "pickrecordlist">
                            <tr id = "require">

                                <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "機種">{!! __('oboundpageLang.machine') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "製程">{!! __('oboundpageLang.process') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "退回原因">{!! __('oboundpageLang.backreason') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "線別">{!! __('oboundpageLang.line') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "預退數量">{!! __('oboundpageLang.backamount') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title9" value = "實際退回數量">{!! __('oboundpageLang.realbackamount') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title10" value = "備註">{!! __('oboundpageLang.mark') !!}</th>
                                <th><input type = "hidden" id = "title11" name = "title11" value = "實退差異原因">{!! __('oboundpageLang.backdiffreason') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title12" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                <th><input type = "hidden" id = "title13" name = "title13" value = "收料人員">{!! __('oboundpageLang.receivepeople') !!}</th>
                                <th><input type = "hidden" id = "title14" name = "title14" value = "收料人員工號">{!! __('oboundpageLang.receivepeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title15" name = "title15" value = "退料人員">{!! __('oboundpageLang.backpeople') !!}</th>
                                <th><input type = "hidden" id = "title16" name = "title16" value = "退料人員工號">{!! __('oboundpageLang.backpeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title17" name = "title17" value = "退料單號">{!! __('oboundpageLang.backlistnum') !!}</th>
                                <th><input type = "hidden" id = "title18" name = "title18" value = "開單時間">{!! __('oboundpageLang.opentime') !!}</th>
                                <th><input type = "hidden" id = "title19" name = "title19" value = "入庫時間">{!! __('oboundpageLang.inboundtime') !!}</th>
                                <th><input type = "hidden" id = "title20" name = "title20" value = "功能狀況">{!! __('oboundpageLang.status') !!}</th>
                                <input type = "hidden" id = "time" name = "time" value = "21">
                            </tr>
                                @foreach($data as $data)
                                <tr id = "{{$data->退料單號}}">
                                    <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->製程}}">{{$data->製程}}</td>
                                    <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->退回原因}}">{{$data->退回原因}}</td>
                                    <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->線別}}">{{$data->線別}}</td>
                                    <td><input type = "hidden" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "{{$data->預退數量}}">{{$data->預退數量}}</td>
                                    <td><input type = "hidden" id = "data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$data->實際退回數量}}">{{$data->實際退回數量}}</td>
                                    <td><input type = "hidden" id = "data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                    <td><input type = "hidden" id = "data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$data->實退差異原因}}">{{$data->實退差異原因}}</td>
                                    <td><input type = "hidden" id = "data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "{{$data->庫別}}">{{$data->庫別}}</td>
                                    <td><input type = "hidden" id = "data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "{{$data->收料人員}}">{{$data->收料人員}}</td>
                                    <td><input type = "hidden" id = "data14{{$loop->index}}" name = "data14{{$loop->index}}" value = "{{$data->收料人員工號}}">{{$data->收料人員工號}}</td>
                                    <td><input type = "hidden" id = "data15{{$loop->index}}" name = "data15{{$loop->index}}" value = "{{$data->退料人員}}">{{$data->退料人員}}</td>
                                    <td><input type = "hidden" id = "data16{{$loop->index}}" name = "data16{{$loop->index}}" value = "{{$data->退料人員工號}}">{{$data->退料人員工號}}</td>
                                    <td><input type = "hidden" id = "data17{{$loop->index}}" name = "data17{{$loop->index}}" value = "{{$data->退料單號}}">{{$data->退料單號}}</td>
                                    <td><input type = "hidden" id = "data18{{$loop->index}}" name = "data18{{$loop->index}}" value = "{{$data->開單時間}}">{{$data->開單時間}}</td>
                                    <td><input type = "hidden" id = "data19{{$loop->index}}" name = "data19{{$loop->index}}" value = "{{$data->入庫時間}}">{{$data->入庫時間}}</td>
                                    <td><input type = "hidden" id = "data20{{$loop->index}}" name = "data20{{$loop->index}}" value = "{{$data->功能狀況}}">{{$data->功能狀況}}</td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                        </div>
                        <br>
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.download') !!}">
                        </form>
                    <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.backrecord')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
