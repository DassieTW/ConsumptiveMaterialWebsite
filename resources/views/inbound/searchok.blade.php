@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/searchok.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.inbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.search') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                        <form method="POST" id = "inboundsearch">
                            @csrf
                            <input type = "hidden" id = "titlename" name = "titlename" value = "入庫查詢">
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.delete') !!}">
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.download') !!}">
                            <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.search')}}'">{!! __('inboundpageLang.return') !!}</button>

                            <table class="table" >
                            <tr>
                                <th>{!! __('inboundpageLang.delete') !!}</th>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "入庫單號">{!! __('inboundpageLang.inlist') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('inboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "入庫數量">{!! __('inboundpageLang.inboundnum') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "儲位">{!! __('inboundpageLang.loc') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "入庫人員">{!! __('inboundpageLang.inpeople') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "客戶別">{!! __('inboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "入庫原因">{!! __('inboundpageLang.inreason') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "入庫時間">{!! __('inboundpageLang.inboundtime') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "備註">{!! __('inboundpageLang.mark') !!}</th>
                                <input type = "hidden" id = "titlecount" name = "titlecount" value = "9">
                            </tr>
                                @foreach($data as $data)
                                <tr id= "{{$loop->index}}" class="isnRows">
                                    <td><input class ="innumber" type="checkbox" id="innumber" name="innumber" style="width:20px;height:20px;" value="{{$loop->index}}"></td>
                                    <td><input type = "hidden" id = "dataa{{$loop->index}}" name = "dataa{{$loop->index}}" value = "{{$data->入庫單號}}">{{$data->入庫單號}}</td>
                                    <td><input type = "hidden" id = "datab{{$loop->index}}" name = "datab{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "datac{{$loop->index}}" name = "datac{{$loop->index}}" value = "{{$data->入庫數量}}">{{$data->入庫數量}}</td>
                                    <td><input type = "hidden" id = "datad{{$loop->index}}" name = "datad{{$loop->index}}" value = "{{$data->儲位}}">{{$data->儲位}}</td>
                                    <td><input type = "hidden" id = "datae{{$loop->index}}" name = "datae{{$loop->index}}" value = "{{$data->入庫人員}}">{{$data->入庫人員}}</td>
                                    <td><input type = "hidden" id = "dataf{{$loop->index}}" name = "dataf{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "datag{{$loop->index}}" name = "datag{{$loop->index}}" value = "{{$data->入庫原因}}">{{$data->入庫原因}}</td>
                                    <td><input type = "hidden" id = "datah{{$loop->index}}" name = "datah{{$loop->index}}" value = "{{$data->入庫時間}}">{{$data->入庫時間}}</td>
                                    <td><input type = "hidden" id = "datai{{$loop->index}}" name = "datai{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->


                        </form>
            </div>
        </div>
</html>
@endsection
