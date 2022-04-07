@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<script src="{{ asset('js/obound/download.js') }}"></script>

<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.pickrecord') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
            </div>
            <div class="card-body">

                        <form id="picktable" method="POST">
                            @csrf
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.download') !!}">

                            <input type = "hidden" id = "titlename" name = "titlename" value = "O庫領料記錄表">
                            <div class="table-responsive">
                        <table class="table" id = "pickrecordlist">
                            <tr>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "機種">{!! __('oboundpageLang.machine') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "製程">{!! __('oboundpageLang.process') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "領用原因">{!! __('oboundpageLang.usereason') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "線別">{!! __('oboundpageLang.line') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "預領數量">{!! __('oboundpageLang.pickamount') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title9" value = "實際領用數量">{!! __('oboundpageLang.realpickamount') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title10" value = "備註">{!! __('oboundpageLang.mark') !!}</th>
                                <th><input type = "hidden" id = "title11" name = "title11" value = "實領差異原因">{!! __('oboundpageLang.diffreason') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title12" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                <th><input type = "hidden" id = "title13" name = "title13" value = "領料人員">{!! __('oboundpageLang.pickpeople') !!}</th>
                                <th><input type = "hidden" id = "title14" name = "title14" value = "領料人員工號">{!! __('oboundpageLang.pickpeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title15" name = "title15" value = "發料人員">{!! __('oboundpageLang.sendpeople') !!}</th>
                                <th><input type = "hidden" id = "title16" name = "title16" value = "發料人員工號">{!! __('oboundpageLang.sendpeoplenum') !!}</th>
                                <th><input type = "hidden" id = "title17" name = "title17" value = "領料單號">{!! __('oboundpageLang.picklistnum') !!}</th>
                                <th><input type = "hidden" id = "title18" name = "title18" value = "開單時間">{!! __('oboundpageLang.opentime') !!}</th>
                                <th><input type = "hidden" id = "title19" name = "title19" value = "出庫時間">{!! __('oboundpageLang.outboundtime') !!}</th>
                                <input type = "hidden" id = "titlecount" name = "titlecount" value = "20">
                            </tr>
                                @foreach($data as $data)
                                <tr id = "{{$data->領料單號}}" class="isnRows">
                                    <td><input type = "hidden" id = "dataa{{$loop->index}}" name = "dataa{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "datab{{$loop->index}}" name = "datab{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "datac{{$loop->index}}" name = "datac{{$loop->index}}" value = "{{$data->製程}}">{{$data->製程}}</td>
                                    <td><input type = "hidden" id = "datad{{$loop->index}}" name = "datad{{$loop->index}}" value = "{{$data->領用原因}}">{{$data->領用原因}}</td>
                                    <td><input type = "hidden" id = "datae{{$loop->index}}" name = "datae{{$loop->index}}" value = "{{$data->線別}}">{{$data->線別}}</td>
                                    <td><input type = "hidden" id = "dataf{{$loop->index}}" name = "dataf{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "datag{{$loop->index}}" name = "datag{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "datah{{$loop->index}}" name = "datah{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "datai{{$loop->index}}" name = "datai{{$loop->index}}" value = "{{$data->預領數量}}">{{$data->預領數量}}</td>
                                    <td><input type = "hidden" id = "dataj{{$loop->index}}" name = "dataj{{$loop->index}}" value = "{{$data->實際領用數量}}">{{$data->實際領用數量}}</td>
                                    <td><input type = "hidden" id = "datak{{$loop->index}}" name = "datak{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                    <td><input type = "hidden" id = "datal{{$loop->index}}" name = "datal{{$loop->index}}" value = "{{$data->實領差異原因}}">{{$data->實領差異原因}}</td>
                                    <td><input type = "hidden" id = "datam{{$loop->index}}" name = "datam{{$loop->index}}" value = "{{$data->庫別}}">{{$data->庫別}}</td>
                                    <td><input type = "hidden" id = "datan{{$loop->index}}" name = "datan{{$loop->index}}" value = "{{$data->領料人員}}">{{$data->領料人員}}</td>
                                    <td><input type = "hidden" id = "datao{{$loop->index}}" name = "datao{{$loop->index}}" value = "{{$data->領料人員工號}}">{{$data->領料人員工號}}</td>
                                    <td><input type = "hidden" id = "datap{{$loop->index}}" name = "datap{{$loop->index}}" value = "{{$data->發料人員}}">{{$data->發料人員}}</td>
                                    <td><input type = "hidden" id = "dataq{{$loop->index}}" name = "dataq{{$loop->index}}" value = "{{$data->發料人員工號}}">{{$data->發料人員工號}}</td>
                                    <td><input type = "hidden" id = "datar{{$loop->index}}" name = "datar{{$loop->index}}" value = "{{$data->領料單號}}">{{$data->領料單號}}</td>
                                    <td><input type = "hidden" id = "datas{{$loop->index}}" name = "datas{{$loop->index}}" value = "{{$data->開單時間}}">{{$data->開單時間}}</td>
                                    <td><input type = "hidden" id = "datat{{$loop->index}}" name = "datat{{$loop->index}}" value = "{{$data->出庫時間}}">{{$data->出庫時間}}</td>
                                </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                    @endforeach

                                </table>
                            </div>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            </form>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.pickrecord')}}'">{!! __('oboundpageLang.return') !!}</button>
                </div>
            </div>
    </html>
    @endsection
