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
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('inbound.changesubmit') }}" method="POST">
                            @csrf
                        <table class="table" id = "inboundsearch">
                            <tr id = "require">
                                <th>{!! __('inboundpageLang.check') !!}</th>
                                <th>{!! __('inboundpageLang.isn') !!}</th>
                                <th>{!! __('inboundpageLang.nowstock') !!}</th>
                                <th>{!! __('inboundpageLang.loc') !!}</th>
                                <th>{!! __('inboundpageLang.client') !!}</th>
                                <th>{!! __('inboundpageLang.updatetime') !!}</th>
                                <th>{!! __('inboundpageLang.changeamount') !!}</th>
                                <th>{!! __('inboundpageLang.newloc') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <tr id = "{{$loop->index}}">
                                    <?php $position =  DB::table('儲位')->pluck('儲存位置');?>
                                    <td><input class ="basic" type="checkbox" id="submit{{$loop->index}}" name="submit{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "stock{{$loop->index}}" name = "stock{{$loop->index}}" value = "{{$data->現有庫存}}">{{$data->現有庫存}}</td>
                                    <td><input type = "hidden" id = "oldposition{{$loop->index}}" name = "oldposition{{$loop->index}}" value = "{{$data->儲位}}">{{$data->儲位}}</td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td>{{$data->最後更新時間}}</td>
                                    <td><input type="number" id ="amount{{$loop->index}}" name="amount{{$loop->index}}" placeholder="{!! __('inboundpageLang.enteramount') !!}"></td>
                                    <td>
                                        <select class="form-control form-control-lg" id = "newposition{{$loop->index}}" name="newposition{{$loop->index}}">
                                        <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc') !!}</option>
                                        @foreach($position as $position)
                                        <option>{{  $position }}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <input type = "hidden" id="time" name = "time" value="{{$loop->count}}">

                                @endforeach


                            </table>
                            @error('stock')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.submit') !!}">
                        </form>

                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.positionchange')}}'">{!! __('inboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
