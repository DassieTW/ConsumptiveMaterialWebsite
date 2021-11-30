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
                <h3>{!! __('oboundpageLang.pickrecord') !!}</h3>
            </div>
            <div class="card-body">

                        <form action="{{ route('obound.download') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "領料記錄表">
                            <div class="table-responsive">
                        <table class="table" id = "pickrecordlist">
                            <tr id = "require">
                                <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "機種">{!! __('oboundpageLang.machine') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "製程">{!! __('oboundpageLang.process') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "領用原因">{!! __('oboundpageLang.usereason') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "線別">{!! __('oboundpageLang.line') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title8" value = "預領數量">{!! __('oboundpageLang.pickamount') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title9" value = "實際領用數量">{!! __('oboundpageLang.realpickamount') !!}</th>
                                <th><input type = "hidden" id = "title11" name = "title10" value = "備註">{!! __('oboundpageLang.mark') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title11" value = "實領差異原因">{!! __('oboundpageLang.diffreason') !!}</th>
                                <th><input type = "hidden" id = "title13" name = "title12" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                <th><input type = "hidden" id = "title14" name = "title13" value = "領料人員">{!! __('oboundpageLang.pickpeople') !!}</th>
                                <th><input type = "hidden" id = "title15" name = "title14" value = "領料人員工號">{!! __('oboundpageLang.pickpeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title16" name = "title15" value = "發料人員">{!! __('oboundpageLang.sendpeople') !!}</th>
                                <th><input type = "hidden" id = "title17" name = "title16" value = "發料人員工號">{!! __('oboundpageLang.sendpeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title18" name = "title17" value = "領料單號">{!! __('oboundpageLang.picklistnum') !!}</th>
                                <th><input type = "hidden" id = "title19" name = "title18" value = "開單時間">{!! __('oboundpageLang.opentime') !!}</th>
                                <th><input type = "hidden" id = "title20" name = "title19" value = "出庫時間">{!! __('oboundpageLang.outboundtime') !!}</th>
                                <input type = "hidden" id = "time" name = "time" value = "20">
                            </tr>
                                @foreach($data as $data)
                                <tr id = "{{$data->領料單號}}">
                                    <td><input type = "hidden" id = "data0{{$loop->index}}" name = "data0{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "data1{{$loop->index}}" name = "data1{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "data2{{$loop->index}}" name = "data2{{$loop->index}}" value = "{{$data->制程}}">{{$data->制程}}</td>
                                    <td><input type = "hidden" id = "data3{{$loop->index}}" name = "data3{{$loop->index}}" value = "{{$data->領用原因}}">{{$data->領用原因}}</td>
                                    <td><input type = "hidden" id = "data4{{$loop->index}}" name = "data4{{$loop->index}}" value = "{{$data->線別}}">{{$data->線別}}</td>
                                    <td><input type = "hidden" id = "data5{{$loop->index}}" name = "data5{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "data6{{$loop->index}}" name = "data6{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "data7{{$loop->index}}" name = "data7{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "data8{{$loop->index}}" name = "data8{{$loop->index}}" value = "{{$data->預領數量}}">{{$data->預領數量}}</td>
                                    <td><input type = "hidden" id = "data9{{$loop->index}}" name = "data9{{$loop->index}}" value = "{{$data->實際領用數量}}">{{$data->實際領用數量}}</td>
                                    <td><input type = "hidden" id = "data10{{$loop->index}}" name = "data10{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                    <td><input type = "hidden" id = "data11{{$loop->index}}" name = "data11{{$loop->index}}" value = "{{$data->實領差異原因}}">{{$data->實領差異原因}}</td>
                                    <td><input type = "hidden" id = "data12{{$loop->index}}" name = "data12{{$loop->index}}" value = "{{$data->庫別}}">{{$data->庫別}}</td>
                                    <td><input type = "hidden" id = "data13{{$loop->index}}" name = "data13{{$loop->index}}" value = "{{$data->領料人員}}">{{$data->領料人員}}</td>
                                    <td><input type = "hidden" id = "data14{{$loop->index}}" name = "data14{{$loop->index}}" value = "{{$data->領料人員工號}}">{{$data->領料人員工號}}</td>
                                    <td><input type = "hidden" id = "data15{{$loop->index}}" name = "data15{{$loop->index}}" value = "{{$data->發料人員}}">{{$data->發料人員}}</td>
                                    <td><input type = "hidden" id = "data16{{$loop->index}}" name = "data16{{$loop->index}}" value = "{{$data->發料人員工號}}">{{$data->發料人員工號}}</td>
                                    <td><input type = "hidden" id = "data17{{$loop->index}}" name = "data17{{$loop->index}}" value = "{{$data->領料單號}}">{{$data->領料單號}}</td>
                                    <td><input type = "hidden" id = "data18{{$loop->index}}" name = "data18{{$loop->index}}" value = "{{$data->開單時間}}">{{$data->開單時間}}</td>
                                    <td><input type = "hidden" id = "data19{{$loop->index}}" name = "data19{{$loop->index}}" value = "{{$data->出庫時間}}">{{$data->出庫時間}}</td>
                                </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                    @endforeach

                                </table>
                            </div>
                            <br>
                                <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.download') !!}">
                            </form>
                       <br>
                    <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.pickrecord')}}'">{!! __('oboundpageLang.return') !!}</button>
                </div>
            </div>
    </html>
    @endsection
