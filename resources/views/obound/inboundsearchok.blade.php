@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/inboundsearch.js') }}"></script>
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
                <h3>{!! __('oboundpageLang.inboundsearch') !!}</h3>
            </div>
            <div class="card-body">

                        <form action="{{ route('obound.delete') }}" method="POST">
                            @csrf
                            <input type = "hidden" id = "title" name = "title" value = "入庫查詢">
                            <div class="table-responsive">
                        <table class="table" id = "inboundsearch">
                            <tr id = "require">
                                <th>{!! __('oboundpageLang.delete') !!}</th>
                                <th><input type = "hidden" id = "title0" name = "title0" value = "入庫單號">{!! __('oboundpageLang.inlist') !!}</th>
                                <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                <th><input type = "hidden" id = "title2" name = "title2" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                <th><input type = "hidden" id = "title3" name = "title3" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                <th><input type = "hidden" id = "title4" name = "title4" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                <th><input type = "hidden" id = "title5" name = "title5" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                <th><input type = "hidden" id = "title6" name = "title6" value = "數量">{!! __('oboundpageLang.inboundnum') !!}</th>
                                <th><input type = "hidden" id = "title7" name = "title7" value = "入庫人員">{!! __('oboundpageLang.inpeople') !!}</th>
                                <th><input type = "hidden" id = "title8" name = "title8" value = "時間">{!! __('oboundpageLang.inboundtime') !!}</th>
                                <th><input type = "hidden" id = "title9" name = "title9" value = "備註">{!! __('oboundpageLang.mark') !!}</th>
                                <th><input type = "hidden" id = "title10" name = "title10" value = "入庫原因">{!! __('oboundpageLang.inreason') !!}</th>
                                <input type = "hidden" id = "time" name = "time" value = "11">
                            </tr>
                                @foreach($data as $data)
                                <tr id= "{{$loop->index}}">
                                    <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->入庫單號}}">{{$data->入庫單號}}</td>
                                    <td><input type = "hidden"  id = "material{{$loop->index}}" name = "material{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden"  id = "name{{$loop->index}}" name = "name{{$loop->index}}" value = "{{$data->品名}}">{{$data->品名}}</td>
                                    <td><input type = "hidden"  name = "data3{{$loop->index}}" value = "{{$data->規格}}">{{$data->規格}}</td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "bound{{$loop->index}}"  name = "bound{{$loop->index}}" value = "{{$data->庫別}}">{{$data->庫別}}</td>
                                    <td><input type = "hidden" id = "amount{{$loop->index}}" name = "amount{{$loop->index}}" value = "{{$data->數量}}">{{$data->數量}}</td>
                                    <td><input type = "hidden"  name = "data7{{$loop->index}}" value = "{{$data->入庫人員}}">{{$data->入庫人員}}</td>
                                    <td><input type = "hidden"  name = "data8{{$loop->index}}" value = "{{$data->時間}}">{{$data->時間}}</td>
                                    <td><input type = "hidden"  name = "data8{{$loop->index}}" value = "{{$data->備註}}">{{$data->備註}}</td>
                                    <td><input type = "hidden"  name = "data8{{$loop->index}}" value = "{{$data->入庫原因}}">{{$data->入庫原因}}</td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            </div>
                            <br>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.delete') !!}">
                        </form>
                    <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.inboundsearch')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
