@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/change.js') }}"></script>
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
        <h3>{!! __('inboundpageLang.locationchange') !!}</h3>
        <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form id="change" method="POST">
                @csrf
                <table class="table" id="inboundsearch">
                    <tr id="require">
                        <th>{!! __('inboundpageLang.check') !!}</th>
                        <th>{!! __('inboundpageLang.isn') !!}</th>
                        <th>{!! __('inboundpageLang.nowstock') !!}</th>
                        <th>{!! __('inboundpageLang.loc') !!}</th>
                        <th>{!! __('inboundpageLang.client') !!}</th>
                        <th>{!! __('inboundpageLang.updatetime') !!}</th>
                        <th>{!! __('inboundpageLang.transferamount') !!}</th>
                        <th>{!! __('inboundpageLang.newloc') !!}</th>
                    </tr>
                    @foreach($data as $data)
                    <tr id="{{$loop->index}}" class="isnRows">
                        <?php $position =  DB::table('儲位')->pluck('儲存位置');?>
                        <td><button class="basic btn btn-primary" id="submit{{$loop->index}}" value="{{$loop->index}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                  </svg></button>
                        </td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td><input type="hidden" id="stock{{$loop->index}}" name="stock{{$loop->index}}"
                                value="{{$data->現有庫存}}">{{$data->現有庫存}}</td>
                        <td><input type="hidden" id="oldposition{{$loop->index}}" name="oldposition{{$loop->index}}"
                                value="{{$data->儲位}}">{{$data->儲位}}</td>
                        <td><input type="hidden" id="client{{$loop->index}}" name="client{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td>{{$data->最後更新時間}}</td>
                        <td><input type="number" style="width: 220px" id="amount{{$loop->index}}" class="form-control"
                                name="amount{{$loop->index}}" placeholder="{!! __('inboundpageLang.enteramount') !!}" min="1">
                        </td>
                        <td>
                            <select class="form-select form-select-lg" id="newposition{{$loop->index}}"
                                name="newposition{{$loop->index}}" style="width: 200px">
                                <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc') !!}
                                </option>
                                @foreach($position as $position)
                                <option>{{ $position }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">

                    @endforeach


                </table>

        </div>
        <br>
        </form>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.positionchange')}}'">{!!
            __('inboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
