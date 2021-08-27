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
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('inbound.delete') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "入庫查詢">
                        <table class="table" id = "inboundsearch">
                            <tr id = "require">
                                <th>{!! __('inboundpageLang.delete') !!}</th>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "入庫單號">{!! __('inboundpageLang.inlist') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('inboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "入庫數量">{!! __('inboundpageLang.inamount') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "儲位">{!! __('inboundpageLang.loc') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "入庫人員">{!! __('inboundpageLang.inpeople') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "客戶別">{!! __('inboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "入庫原因">{!! __('inboundpageLang.inreason') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "入庫時間">{!! __('inboundpageLang.intime') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "備註">{!! __('inboundpageLang.mark') !!}</th>
                                <input type = "hidden" id = "time" name = "time" value = "9">
                            </tr>
                                @foreach($data as $data)
                                <tr id= "{{$loop->index}}">
                                    <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->入庫單號}}">{{$data->入庫單號}}</td>
                                    <input type = "hidden"  name = "data0{{$loop->index}}" value = "{{$data->入庫單號}}">
                                    <td><input type = "hidden"  name = "data1{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden"  name = "data2{{$loop->index}}" value = "{{$data->入庫數量}}">{{$data->入庫數量}}</td>
                                    <td><input type = "hidden"  name = "data3{{$loop->index}}" value = "{{$data->儲位}}">{{$data->儲位}}</td>
                                    <td><input type = "hidden"  name = "data4{{$loop->index}}" value = "{{$data->入庫人員}}">{{$data->入庫人員}}</td>
                                    <td><input type = "hidden"  name = "data5{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden"  name = "data6{{$loop->index}}" value = "{{$data->入庫原因}}">{{$data->入庫原因}}</td>
                                    <td><input type = "hidden"  name = "data7{{$loop->index}}" value = "{{$data->入庫時間}}">{{$data->入庫時間}}</td>
                                    <td><input type = "hidden"  name = "data8{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.delete') !!}">
                            <input type = "submit" id = "download" name = "download" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.download') !!}">
                        </form>

                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.search')}}'">{!! __('inboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
