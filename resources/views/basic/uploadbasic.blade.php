@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<script src="{{ asset('js/basic/uploadbasic.js') }}"></script>

<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.newbasicInfo') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form id="uploadbasic" method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    @if ($choose === '廠別')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "廠別">{!! __('basicInfoLang.factory') !!}</th>

                                    @elseif($choose === '客戶別')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('basicInfoLang.client') !!}</th>

                                    @elseif($choose === '機種')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "機種">{!! __('basicInfoLang.machine') !!}</th>

                                    @elseif($choose === '製程')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "製程">{!! __('basicInfoLang.process') !!}</th>

                                    @elseif($choose === '線別')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "線別">{!! __('basicInfoLang.line') !!}</th>

                                    @elseif($choose === '領用部門')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "領用部門">{!! __('basicInfoLang.usedep') !!}</th>

                                    @elseif($choose === '領用原因')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "領用原因">{!! __('basicInfoLang.usereason') !!}</th>

                                    @elseif($choose === '入庫原因')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "入庫原因">{!! __('basicInfoLang.inreason') !!}</th>

                                    @elseif($choose === '儲位')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "儲位">{!! __('basicInfoLang.loc') !!}</th>

                                    @elseif($choose === '發料部門')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "發料部門">{!! __('basicInfoLang.senddep') !!}</th>

                                    @elseif($choose === 'O庫')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "O庫">{!! __('basicInfoLang.obound') !!}</th>

                                    @elseif($choose === '退回原因')
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "退回原因">{!! __('basicInfoLang.returnreason') !!}</th>

                                    @endif

                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <td><input type = "text"  name = "data0{{$loop->index}}" value = "{{$row[0]}}"></td>

                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.add') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">{!! __('basicInfoLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
