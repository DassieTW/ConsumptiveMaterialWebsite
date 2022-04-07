@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* for single line table with over-flow , SAP style as asked */
    table {
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
<script src="{{ asset('js/bu/searchlist.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('bupagelang.bu') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('bupagelang.searchlist') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                        <form  method="POST" id = "bulist">
                            @csrf
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('bupagelang.delete') !!}">
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('bupagelang.download') !!}">
                            <input type = "hidden" id = "titlename" name = "titlename" value = "調撥單查詢">
                        <table class="table" id = "inboundsearch">
                            <thead>
                            <tr id = "require">
                                <th>{!! __('bupagelang.delete') !!}</th>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "調撥單號">{!! __('bupagelang.dblist') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "狀態">{!! __('bupagelang.status') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "撥出廠區">{!! __('bupagelang.outfactory') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "接收廠區">{!! __('bupagelang.receivefac') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "料號">{!! __('bupagelang.isn') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "品名">{!! __('bupagelang.pName') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "規格">{!! __('bupagelang.format') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "單位">{!! __('bupagelang.unit') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "庫存">{!! __('bupagelang.stock') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title9" value = "調撥數量">{!! __('bupagelang.tranamount') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title10" value = "調撥人">{!! __('bupagelang.transpeople') !!}</th>
                                <th><input type = "hidden" id = "title11" name = "title11" value = "接收人">{!! __('bupagelang.receivepeople') !!}</th>
                                <th><input type = "hidden" id = "title12" name = "title12" value = "撥出數量">{!! __('bupagelang.transamount') !!}</th>
                                <th><input type = "hidden" id = "title13" name = "title13" value = "接收數量">{!! __('bupagelang.receiveamount') !!}</th>
                                <th><input type = "hidden" id = "title14" name = "title14" value = "開單時間">{!! __('bupagelang.opentime') !!}</th>
                                <th><input type = "hidden" id = "title15" name = "title15" value = "出庫時間">{!! __('bupagelang.outboundtime') !!}</th>
                                <th><input type = "hidden" id = "title16" name = "title16" value = "入庫時間">{!! __('bupagelang.inboundtime') !!}</th>
                                <input type = "hidden" id = "titlecount" name = "titlecount" value = "17">
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($data as $data)
                                <tr id= "list{{$loop->index}}" class="isnRows">
                                    <td><input class ="basic" type="checkbox" id="check{{$loop->index}}" name="check{{$loop->index}}" style="width:20px;height:20px;"  value="{{$loop->index}}"></td>
                                    <td><input type = "hidden" id = "dataa{{$loop->index}}" name = "dataa{{$loop->index}}" value = "{{$data->調撥單號}}">{{$data->調撥單號}}</td>
                                    <td><input type = "hidden" id = "datab{{$loop->index}}" name = "datab{{$loop->index}}" value = "{{$data->狀態}}">{{$data->狀態}}</td>
                                    <td><input type = "hidden" id = "datac{{$loop->index}}" name = "datac{{$loop->index}}" value = "{{$data->撥出廠區}}">{{$data->撥出廠區}}</td>
                                    <td><input type = "hidden" id = "datad{{$loop->index}}" name = "datad{{$loop->index}}" value = "{{$data->接收廠區}}">{{$data->接收廠區}}</td>
                                    <td><input type = "hidden" id = "datae{{$loop->index}}" name = "datae{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "dataf{{$loop->index}}" name = "dataf{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden" id = "datag{{$loop->index}}" name = "datag{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "datah{{$loop->index}}" name = "datah{{$loop->index}}" value = "{{$data->單位}}">{{$data->單位}}</td>
                                    <td><input type = "hidden" id = "datai{{$loop->index}}" name = "datai{{$loop->index}}" value = "{{$data->庫存}}">{{$data->庫存}}</td>
                                    <td><input type = "hidden" id = "dataj{{$loop->index}}" name = "dataj{{$loop->index}}" value = "{{$data->調撥數量}}">{{$data->調撥數量}}</td>
                                    <td><input type = "hidden" id = "datak{{$loop->index}}" name = "datak{{$loop->index}}" value = "{{$data->調撥人}}">{{$data->調撥人}}</td>
                                    <td><input type = "hidden" id = "datal{{$loop->index}}" name = "datal{{$loop->index}}" value = "{{$data->接收人}}">{{$data->接收人}}</td>
                                    <td><input type = "hidden" id = "datam{{$loop->index}}" name = "datam{{$loop->index}}" value = "{{$data->撥出數量}}">{{$data->撥出數量}}</td>
                                    <td><input type = "hidden" id = "datan{{$loop->index}}" name = "datan{{$loop->index}}" value = "{{$data->接收數量}}">{{$data->接收數量}}</td>
                                    <td><input type = "hidden" id = "datao{{$loop->index}}" name = "datao{{$loop->index}}" value = "{{$data->開單時間}}">{{$data->開單時間}}</td>
                                    <td><input type = "hidden" id = "datap{{$loop->index}}" name = "datap{{$loop->index}}" value = "{{$data->出庫時間}}">{{$data->出庫時間}}</td>
                                    <td><input type = "hidden" id = "dataq{{$loop->index}}" name = "dataq{{$loop->index}}" value = "{{$data->入庫時間}}">{{$data->入庫時間}}</td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        </form>
                {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.searchlist')}}'">{!! __('bupagelang.return') !!}</button> --}}
            </div>
        </div>
</html>
@endsection
